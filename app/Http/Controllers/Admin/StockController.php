<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    /**
     * Display a listing of stock inventory items across warehouses.
     */
    public function index(Request $request): View
    {
        $query = Stock::with('warehouse');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('rack_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'low_stock') {
                $query->lowStock();
            } elseif ($status === 'out_of_stock') {
                $query->outOfStock();
            } else {
                $query->where('status', $status);
            }
        }

        $stocks = $query->latest()->get();
        $warehouses = Warehouse::orderBy('name')->get();

        // Calculate key inventory metrics
        $totalItems = Stock::count();
        $totalQuantity = Stock::sum('quantity');
        $lowStockCount = Stock::lowStock()->count() + Stock::outOfStock()->count();
        $totalValuation = Stock::all()->sum(function ($s) {
            return $s->quantity * $s->unit_cost;
        });

        return view('admin.stocks.index', compact(
            'stocks',
            'warehouses',
            'totalItems',
            'totalQuantity',
            'lowStockCount',
            'totalValuation'
        ));
    }

    /**
     * Show the form for creating a new stock inventory item.
     */
    public function create(): View
    {
        if (!auth()->user()?->canDo(['products.create', 'inventory.create', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to create stock/product items.');
        }

        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'Electronics & Energy',
                'CPUs & Processors',
                'RAM & Memory Modules',
                'SSDs & Storage Drives',
                'Graphics Cards & GPUs',
                'Laptop Parts & Components',
                'General Goods'
            ];
        }

        return view('admin.stocks.create', compact('warehouses', 'categories'));
    }

    /**
     * Store a newly created stock item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()?->canDo(['products.create', 'inventory.create', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to create stock/product items.');
        }
        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'sku' => 'required|string|max:100',
            'product_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'cpu_brand' => 'nullable|string|max:100',
            'cpu_model' => 'nullable|string|max:100',
            'generation' => 'nullable|string|max:100',
            'cores' => 'nullable|string|max:100',
            'threads' => 'nullable|string|max:100',
            'base_clock' => 'nullable|string|max:100',
            'turbo_boost' => 'nullable|string|max:100',
            'cache' => 'nullable|string|max:100',
            'tdp' => 'nullable|string|max:100',
            'socket' => 'nullable|string|max:100',
            'integrated_graphics' => 'nullable|string|max:100',
            'ram' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'reserved_quantity' => 'nullable|integer|min:0',
            'min_reorder_level' => 'required|integer|min:0',
            'max_capacity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0|gt:unit_cost',
            'rack_location' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
        ], [
            'retail_price.gt' => 'Retail Selling Price ($:input) must be higher than the Cost Price! Selling at or below cost price is not allowed.',
        ]);

        $validated['reserved_quantity'] = $validated['reserved_quantity'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stocks', 'public');
        }
        
        // Build dynamic hardware & product spec details structure
        $details = [];

        // 1. Process dynamic spec_keys[] and spec_values[]
        if ($request->has('spec_keys') && is_array($request->input('spec_keys'))) {
            $keys = $request->input('spec_keys');
            $values = $request->input('spec_values', []);

            foreach ($keys as $i => $k) {
                $kTrim = trim((string) $k);
                $vTrim = trim((string) ($values[$i] ?? ''));
                if ($kTrim !== '' && $vTrim !== '') {
                    $details[$kTrim] = $vTrim;
                }
            }
        }

        // 2. Process legacy/fixed named spec inputs if provided
        $specKeys = [
            'cpu_brand', 'cpu_model', 'generation', 'cores', 'threads', 
            'base_clock', 'turbo_boost', 'cache', 'tdp', 'socket', 
            'integrated_graphics', 'ram', 'storage'
        ];
        foreach ($specKeys as $key) {
            if ($request->filled($key) && !isset($details[$key])) {
                $details[$key] = $request->input($key);
            }
        }
        $validated['details'] = $details;

        // Parse volume tier prices
        $tierPrices = [];
        if ($request->has('tier_min_qty') && is_array($request->input('tier_min_qty'))) {
            $minArr = $request->input('tier_min_qty');
            $maxArr = $request->input('tier_max_qty', []);
            $priceArr = $request->input('tier_price', []);

            foreach ($minArr as $index => $minQty) {
                if ($minQty !== null && $minQty !== '' && isset($priceArr[$index]) && $priceArr[$index] !== '') {
                    $tierPrices[] = [
                        'min_qty' => (int) $minQty,
                        'max_qty' => isset($maxArr[$index]) && $maxArr[$index] !== '' && $maxArr[$index] !== null ? (int) $maxArr[$index] : null,
                        'price' => (float) $priceArr[$index],
                    ];
                }
            }
        }
        $validated['tier_prices'] = $tierPrices;

        // Determine initial stock status
        if ($validated['quantity'] <= 0) {
            $validated['status'] = 'out_of_stock';
        } elseif ($validated['quantity'] <= $validated['min_reorder_level']) {
            $validated['status'] = 'low_stock';
        } elseif ($validated['quantity'] >= $validated['max_capacity']) {
            $validated['status'] = 'overstocked';
        } else {
            $validated['status'] = 'in_stock';
        }

        $stock = Stock::create($validated);

        return redirect()->route('admin.stocks.index')
            ->with('success', "Stock item '{$stock->product_name}' ({$stock->sku}) added successfully.");
    }

    /**
     * Display the specified stock item.
     */
    public function show(Stock $stock): View
    {
        $stock->load('warehouse');

        return view('admin.stocks.show', compact('stock'));
    }

    /**
     * Show the form for editing the specified stock item.
     */
    public function edit(Stock $stock): View
    {
        if (!auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to edit stock/product items.');
        }

        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'Electronics & Energy',
                'CPUs & Processors',
                'RAM & Memory Modules',
                'SSDs & Storage Drives',
                'Graphics Cards & GPUs',
                'Laptop Parts & Components',
                'General Goods'
            ];
        }

        return view('admin.stocks.edit', compact('stock', 'warehouses', 'categories'));
    }

    /**
     * Update the specified stock item in storage.
     */
    public function update(Request $request, Stock $stock): RedirectResponse
    {
        if (!auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to edit stock/product items.');
        }

        $validated = $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'sku' => 'required|string|max:100',
            'product_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:5000',
            'cpu_brand' => 'nullable|string|max:100',
            'cpu_model' => 'nullable|string|max:100',
            'generation' => 'nullable|string|max:100',
            'cores' => 'nullable|string|max:100',
            'threads' => 'nullable|string|max:100',
            'base_clock' => 'nullable|string|max:100',
            'turbo_boost' => 'nullable|string|max:100',
            'cache' => 'nullable|string|max:100',
            'tdp' => 'nullable|string|max:100',
            'socket' => 'nullable|string|max:100',
            'integrated_graphics' => 'nullable|string|max:100',
            'ram' => 'nullable|string|max:100',
            'storage' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'reserved_quantity' => 'nullable|integer|min:0',
            'min_reorder_level' => 'required|integer|min:0',
            'max_capacity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'retail_price' => 'required|numeric|min:0|gt:unit_cost',
            'rack_location' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
        ], [
            'retail_price.gt' => 'Retail Selling Price ($:input) must be higher than the Cost Price! Selling at or below cost price is not allowed.',
        ]);

        $validated['reserved_quantity'] = $validated['reserved_quantity'] ?? 0;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stocks', 'public');
        }

        // Build dynamic hardware & product spec details structure
        $details = [];

        // 1. Process dynamic spec_keys[] and spec_values[]
        if ($request->has('spec_keys') && is_array($request->input('spec_keys'))) {
            $keys = $request->input('spec_keys');
            $values = $request->input('spec_values', []);

            foreach ($keys as $i => $k) {
                $kTrim = trim((string) $k);
                $vTrim = trim((string) ($values[$i] ?? ''));
                if ($kTrim !== '' && $vTrim !== '') {
                    $details[$kTrim] = $vTrim;
                }
            }
        }

        // 2. Fallback to fixed named spec inputs if provided
        $specKeys = [
            'cpu_brand', 'cpu_model', 'generation', 'cores', 'threads', 
            'base_clock', 'turbo_boost', 'cache', 'tdp', 'socket', 
            'integrated_graphics', 'ram', 'storage'
        ];
        foreach ($specKeys as $key) {
            if ($request->filled($key) && !isset($details[$key])) {
                $details[$key] = $request->input($key);
            }
        }
        $validated['details'] = $details;

        // Parse volume tier prices
        $tierPrices = [];
        if ($request->has('tier_min_qty') && is_array($request->input('tier_min_qty'))) {
            $minArr = $request->input('tier_min_qty');
            $maxArr = $request->input('tier_max_qty', []);
            $priceArr = $request->input('tier_price', []);

            foreach ($minArr as $index => $minQty) {
                if ($minQty !== null && $minQty !== '' && isset($priceArr[$index]) && $priceArr[$index] !== '') {
                    $tierPrices[] = [
                        'min_qty' => (int) $minQty,
                        'max_qty' => isset($maxArr[$index]) && $maxArr[$index] !== '' && $maxArr[$index] !== null ? (int) $maxArr[$index] : null,
                        'price' => (float) $priceArr[$index],
                    ];
                }
            }
        }
        $validated['tier_prices'] = $tierPrices;

        // Auto determine status based on quantities
        if ($validated['quantity'] <= 0) {
            $validated['status'] = 'out_of_stock';
        } elseif ($validated['quantity'] <= $validated['min_reorder_level']) {
            $validated['status'] = 'low_stock';
        } elseif ($validated['quantity'] >= $validated['max_capacity']) {
            $validated['status'] = 'overstocked';
        } else {
            $validated['status'] = 'in_stock';
        }

        $stock->update($validated);

        return redirect()->route('admin.stocks.index')
            ->with('success', "Stock item '{$stock->product_name}' updated successfully.");
    }

    /**
     * Perform quick stock quantity adjustment.
     */
    public function adjust(Request $request, Stock $stock): RedirectResponse
    {
        if (!auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to adjust stock items.');
        }

        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'adjustment_amount' => 'required|integer|min:1',
            'adjustment_reason' => 'nullable|string|max:255',
        ]);

        $amount = (int) $validated['adjustment_amount'];
        $newQuantity = $stock->quantity;

        if ($validated['adjustment_type'] === 'add') {
            $newQuantity += $amount;
        } elseif ($validated['adjustment_type'] === 'subtract') {
            $newQuantity = max(0, $newQuantity - $amount);
        } elseif ($validated['adjustment_type'] === 'set') {
            $newQuantity = $amount;
        }

        // Determine updated status
        $newStatus = 'in_stock';
        if ($newQuantity <= 0) {
            $newStatus = 'out_of_stock';
        } elseif ($newQuantity <= $stock->min_reorder_level) {
            $newStatus = 'low_stock';
        } elseif ($newQuantity >= $stock->max_capacity) {
            $newStatus = 'overstocked';
        }

        $stock->update([
            'quantity' => $newQuantity,
            'status' => $newStatus,
            'notes' => trim(($stock->notes ? $stock->notes . "\n" : '') . "Adjustment: {$validated['adjustment_type']} {$amount} units. Reason: " . ($validated['adjustment_reason'] ?? 'Manual adjustment')),
        ]);

        return back()->with('success', "Stock adjusted for '{$stock->product_name}'. New total: {$newQuantity} units.");
    }

    /**
     * Display Stock Adjustments Management.
     */
    public function adjustments(Request $request): View
    {
        $query = Stock::with('warehouse');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('rack_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'low_stock') {
                $query->lowStock();
            } elseif ($status === 'out_of_stock') {
                $query->outOfStock();
            } else {
                $query->where('status', $status);
            }
        }

        $stocks = $query->latest()->get();
        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();

        // Key inventory & adjustment metrics
        $totalItems = Stock::count();
        $totalQuantity = Stock::sum('quantity');
        $lowStockCount = Stock::lowStock()->count() + Stock::outOfStock()->count();
        $totalValuation = Stock::all()->sum(fn($s) => $s->quantity * $s->unit_cost);

        return view('admin.stocks.adjustments', compact(
            'stocks',
            'warehouses',
            'totalItems',
            'totalQuantity',
            'lowStockCount',
            'totalValuation'
        ));
    }

    /**
     * Remove the specified stock item from storage.
     */
    public function destroy(Stock $stock): RedirectResponse
    {
        if (!auth()->user()?->canDo(['products.delete', 'inventory.delete', 'manage_products'])) {
            abort(403, 'Unauthorized action. Admin permission required to delete stock/product items.');
        }

        $name = $stock->product_name;
        $stock->delete();

        return redirect()->route('admin.stocks.index')
            ->with('success', "Stock item '{$name}' deleted successfully.");
    }

    /**
     * Display Stock In (Inventory Intake / Restock Receiving) Management.
     */
    public function stockIn(Request $request): View
    {
        $query = Stock::with('warehouse');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        $stocks = $query->latest()->get();
        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $suppliers = Supplier::where('status', 'active')->orderBy('name')->get();

        $totalIntakeUnits = Stock::sum('quantity');
        $totalItemsCount = Stock::count();
        $warehouseCount = Warehouse::where('status', 'active')->count();
        $totalValuation = Stock::all()->sum(fn($s) => $s->quantity * $s->unit_cost);

        return view('admin.stocks.in', compact(
            'stocks',
            'warehouses',
            'categories',
            'suppliers',
            'totalIntakeUnits',
            'totalItemsCount',
            'warehouseCount',
            'totalValuation'
        ));
    }

    /**
     * Process a Stock In intake entry.
     */
    public function processStockIn(Request $request): RedirectResponse
    {
        $isNew = $request->input('intake_mode') === 'new';

        if ($isNew) {
            $validated = $request->validate([
                'sku' => 'required|string|max:100|unique:stocks,sku',
                'product_name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:4096',
                'short_description' => 'nullable|string|max:500',
                'description' => 'nullable|string|max:5000',
                'cpu_brand' => 'nullable|string|max:100',
                'cpu_model' => 'nullable|string|max:100',
                'category' => 'required|string|max:100',
                'warehouse_id' => 'required|exists:warehouses,id',
                'quantity' => 'required|integer|min:1',
                'unit_cost' => 'required|numeric|min:0',
                'retail_price' => 'nullable|numeric|min:0|gt:unit_cost',
                'min_reorder_level' => 'nullable|integer|min:0',
                'max_capacity' => 'nullable|integer|min:1',
                'supplier_name' => 'nullable|string|max:255',
                'reference_no' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ], [
                'retail_price.gt' => 'Retail Selling Price must be higher than the Cost Price! Selling at or below cost price is not allowed.',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stocks', 'public');
            }

            $minReorder = $validated['min_reorder_level'] ?? 10;
            $maxCap = $validated['max_capacity'] ?? 500;
            $qty = (int) $validated['quantity'];

            $status = 'in_stock';
            if ($qty >= $maxCap) {
                $status = 'overstocked';
            } elseif ($qty <= $minReorder) {
                $status = 'low_stock';
            }

            $ref = $validated['reference_no'] ? "Ref #{$validated['reference_no']}" : "Initial Stock In";
            $supplier = $validated['supplier_name'] ? " from {$validated['supplier_name']}" : "";

            $details = [];
            if (!empty($validated['cpu_brand'])) { $details['cpu_brand'] = $validated['cpu_brand']; }
            if (!empty($validated['cpu_model'])) { $details['cpu_model'] = $validated['cpu_model']; }

            // Parse volume tier prices if passed
            $tierPrices = [];
            if ($request->has('tier_min_qty') && is_array($request->input('tier_min_qty'))) {
                $minArr = $request->input('tier_min_qty');
                $maxArr = $request->input('tier_max_qty', []);
                $priceArr = $request->input('tier_price', []);

                foreach ($minArr as $index => $minQty) {
                    if ($minQty !== null && $minQty !== '' && isset($priceArr[$index]) && $priceArr[$index] !== '') {
                        $tierPrices[] = [
                            'min_qty' => (int) $minQty,
                            'max_qty' => isset($maxArr[$index]) && $maxArr[$index] !== '' && $maxArr[$index] !== null ? (int) $maxArr[$index] : null,
                            'price' => (float) $priceArr[$index],
                        ];
                    }
                }
            }

            $stock = Stock::create([
                'warehouse_id' => $validated['warehouse_id'],
                'sku' => strtoupper($validated['sku']),
                'product_name' => $validated['product_name'],
                'image' => $imagePath,
                'short_description' => $validated['short_description'] ?? null,
                'description' => $validated['description'] ?? null,
                'details' => $details,
                'category' => $validated['category'],
                'quantity' => $qty,
                'reserved_quantity' => 0,
                'min_reorder_level' => $minReorder,
                'max_capacity' => $maxCap,
                'unit_cost' => $validated['unit_cost'],
                'retail_price' => $validated['retail_price'] ?? 0.00,
                'tier_prices' => $tierPrices,
                'status' => $status,
                'notes' => "[NEW ITEM STOCK IN] Initial intake +{$qty} units ({$ref}{$supplier}). " . ($validated['notes'] ?? ''),
            ]);

            return back()->with('success', "New item '{$stock->product_name}' ({$stock->sku}) registered & initial Stock In of +{$qty} units recorded successfully!");
        } else {
            $validated = $request->validate([
                'stock_id' => 'required|exists:stocks,id',
                'quantity' => 'required|integer|min:1',
                'supplier_name' => 'nullable|string|max:255',
                'reference_no' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $stock = Stock::findOrFail($validated['stock_id']);
            $newQty = $stock->quantity + $validated['quantity'];

            $newStatus = 'in_stock';
            if ($newQty >= $stock->max_capacity) {
                $newStatus = 'overstocked';
            }

            $ref = $validated['reference_no'] ? "Ref #{$validated['reference_no']}" : "Stock In Entry";
            $supplier = $validated['supplier_name'] ? " from {$validated['supplier_name']}" : "";

            $stock->update([
                'quantity' => $newQty,
                'status' => $newStatus,
                'notes' => trim(($stock->notes ? $stock->notes . "\n" : '') . "[STOCK IN] +{$validated['quantity']} units ({$ref}{$supplier}). " . ($validated['notes'] ?? '')),
            ]);

            return back()->with('success', "Stock In recorded successfully! Received +{$validated['quantity']} units of '{$stock->product_name}'. New Total: {$newQty} units.");
        }
    }

    /**
     * Display Stock Out (Inventory Dispatch / Shipment Outflow) Management.
     */
    public function stockOut(Request $request): View
    {
        $query = Stock::with('warehouse');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }

        $stocks = $query->latest()->get();
        $warehouses = Warehouse::where('status', 'active')->orderBy('name')->get();
        $customers = Customer::orderBy('company_name', 'asc')->get();

        $totalStockUnits = Stock::sum('quantity');
        $outOfStockCount = Stock::outOfStock()->count();
        $lowStockCount = Stock::lowStock()->count();
        $totalValuation = Stock::all()->sum(fn($s) => $s->quantity * $s->unit_cost);

        return view('admin.stocks.out', compact(
            'stocks',
            'warehouses',
            'customers',
            'totalStockUnits',
            'outOfStockCount',
            'lowStockCount',
            'totalValuation'
        ));
    }

    /**
     * Process a Stock Out dispatch entry.
     */
    public function processStockOut(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'order_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $stock = Stock::findOrFail($validated['stock_id']);

        if ($stock->quantity < $validated['quantity']) {
            return back()->withErrors(['quantity' => "Insufficient stock quantity! Maximum available to dispatch is {$stock->quantity} units."])->withInput();
        }

        $newQty = max(0, $stock->quantity - $validated['quantity']);

        $newStatus = 'in_stock';
        if ($newQty <= 0) {
            $newStatus = 'out_of_stock';
        } elseif ($newQty <= $stock->min_reorder_level) {
            $newStatus = 'low_stock';
        }

        $order = $validated['order_no'] ? "Order #{$validated['order_no']}" : "Stock Out Dispatch";
        $customer = $validated['customer_name'] ? " to {$validated['customer_name']}" : "";

        $stock->update([
            'quantity' => $newQty,
            'status' => $newStatus,
            'notes' => trim(($stock->notes ? $stock->notes . "\n" : '') . "[STOCK OUT] -{$validated['quantity']} units ({$order}{$customer}). " . ($validated['notes'] ?? '')),
        ]);

        return back()->with('success', "Stock Out recorded successfully! Dispatched -{$validated['quantity']} units of '{$stock->product_name}'. Remaining Total: {$newQty} units.");
    }
}
