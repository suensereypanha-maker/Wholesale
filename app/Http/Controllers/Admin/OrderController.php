<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of wholesale customer orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with('customer', 'items');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('customer_code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $orders = $query->latest('order_date')->paginate(10)->withQueryString();

        // Metrics & KPI summaries
        $totalOrdersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $processingOrdersCount = Order::where('status', 'processing')->count();
        $shippedOrdersCount = Order::where('status', 'shipped')->count();
        $deliveredOrdersCount = Order::where('status', 'delivered')->count();
        $totalSalesAmount = Order::sum('total_amount');

        return view('admin.orders.index', compact(
            'orders',
            'totalOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'shippedOrdersCount',
            'deliveredOrdersCount',
            'totalSalesAmount'
        ));
    }

    /**
     * Show form for creating a new B2B Wholesale Customer Order.
     */
    public function create(): View
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $stocks = Stock::where('quantity', '>', 0)->orderBy('product_name')->get();

        $nextId = (Order::max('id') ?? 0) + 1;
        $suggestedOrderNumber = 'ORD-2026-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        return view('admin.orders.create', compact('customers', 'stocks', 'suggestedOrderNumber'));
    }

    /**
     * Store a newly created B2B Wholesale Customer Order.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:50|unique:orders,order_number',
            'customer_id' => 'required|exists:customers,id',
            'payment_terms' => 'required|string|max:100',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:unpaid,partially_paid,paid',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'nullable|exists:stocks,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        $subtotal = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $itemSubtotal;

            $itemsData[] = [
                'stock_id' => $item['stock_id'] ?? null,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $itemSubtotal,
            ];
        }

        // Apply customer's Wholesale discount percentage
        $discountPercent = $customer->wholesale_discount ?? 0;
        $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
        $taxAmount = round(($subtotal - $discountAmount) * 0.05, 2); // 5% standard tax
        $totalAmount = round($subtotal - $discountAmount + $taxAmount, 2);

        $order = Order::create([
            'order_number' => $validated['order_number'],
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_terms' => $validated['payment_terms'],
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'shipping_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->country,
            'notes' => $validated['notes'],
            'order_date' => now(),
            'due_date' => now()->addDays(30),
        ]);

        foreach ($itemsData as $itemData) {
            $order->items()->create($itemData);

            // Deduct stock if stock_id is selected
            if (!empty($itemData['stock_id'])) {
                $stock = Stock::find($itemData['stock_id']);
                if ($stock && $stock->quantity >= $itemData['quantity']) {
                    $stock->decrement('quantity', $itemData['quantity']);
                }
            }
        }

        // Update customer total orders & total spent
        $customer->increment('total_orders');
        $customer->increment('total_spent', $totalAmount);

        return redirect()->route('admin.orders.index')
            ->with('success', "B2B Wholesale Order '{$order->order_number}' for {$customer->name} ({$customer->company_name}) created successfully.");
    }

    /**
     * Display a listing of orders placed specifically by registered frontend customers.
     */
    public function registeredOrders(Request $request): View
    {
        $query = Order::whereNotNull('user_id')->with('customer', 'items', 'user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%")
                         ->orWhere('customer_code', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $orders = $query->latest('order_date')->paginate(10)->withQueryString();

        $totalOrdersCount = Order::whereNotNull('user_id')->count();
        $pendingOrdersCount = Order::whereNotNull('user_id')->where('status', 'pending')->count();
        $processingOrdersCount = Order::whereNotNull('user_id')->where('status', 'processing')->count();
        $shippedOrdersCount = Order::whereNotNull('user_id')->where('status', 'shipped')->count();
        $deliveredOrdersCount = Order::whereNotNull('user_id')->where('status', 'delivered')->count();
        $totalSalesAmount = Order::whereNotNull('user_id')->sum('total_amount');

        $isCustomerRegisterOrders = true;

        return view('admin.orders.index', compact(
            'orders',
            'totalOrdersCount',
            'pendingOrdersCount',
            'processingOrdersCount',
            'shippedOrdersCount',
            'deliveredOrdersCount',
            'totalSalesAmount',
            'isCustomerRegisterOrders'
        ));
    }

    /**
     * Display specified wholesale customer order details.
     */
    public function show($id): View
    {
        $order = Order::with('customer', 'items.stock', 'user')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show form for editing an existing B2B Wholesale Order.
     */
    public function edit($id): View
    {
        $order = Order::with('customer', 'items.stock')->findOrFail($id);
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $stocks = Stock::orderBy('product_name')->get();

        return view('admin.orders.edit', compact('order', 'customers', 'stocks'));
    }

    /**
     * Update specified wholesale customer order in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'payment_terms' => 'required|string|max:100',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:unpaid,partially_paid,paid',
            'shipping_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.stock_id' => 'nullable|exists:stocks,id',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        // Delete existing items
        $order->items()->delete();

        $subtotal = 0;
        $itemsData = [];

        foreach ($validated['items'] as $item) {
            $itemSubtotal = $item['quantity'] * $item['unit_price'];
            $subtotal += $itemSubtotal;

            $itemsData[] = [
                'stock_id' => $item['stock_id'] ?? null,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $itemSubtotal,
            ];
        }

        $discountPercent = $customer->wholesale_discount ?? 0;
        $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
        $taxAmount = round(($subtotal - $discountAmount) * 0.05, 2);
        $totalAmount = round($subtotal - $discountAmount + $taxAmount, 2);

        $order->update([
            'customer_id' => $customer->id,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_terms' => $validated['payment_terms'],
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'shipping_address' => $validated['shipping_address'] ?? ($customer->address . ', ' . $customer->city),
            'notes' => $validated['notes'],
        ]);

        foreach ($itemsData as $itemData) {
            $order->items()->create($itemData);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', "Order #{$order->order_number} updated successfully.");
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $order = Order::with('items')->findOrFail($id);
        $orderNumber = $order->order_number;

        // Restore stock quantities
        foreach ($order->items as $item) {
            if ($item->stock_id) {
                Stock::where('id', $item->stock_id)->increment('quantity', $item->quantity);
            }
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$orderNumber} deleted successfully.");
    }

    /**
     * Update order status or payment status.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|string|in:unpaid,partially_paid,paid',
        ]);

        $order = Order::findOrFail($id);
        $order->update($validated);

        return redirect()->back()
            ->with('success', "Order #{$order->order_number} status updated to " . ucfirst($validated['status']) . ".");
    }
}

