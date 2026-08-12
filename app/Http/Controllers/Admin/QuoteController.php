<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuoteController extends Controller
{
    /**
     * Display a listing of B2B Quotes & Inquiries.
     */
    public function index(Request $request): View
    {
        $query = Quote::with('customer', 'stock', 'user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('quote_number', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $quotes = $query->latest()->paginate(10)->withQueryString();

        // Metrics & KPI Summary
        $totalQuotesCount = Quote::count();
        $pendingQuotesCount = Quote::whereIn('status', ['pending', 'under_review'])->count();
        $quotedCount = Quote::where('status', 'quoted')->count();
        $convertedCount = Quote::where('status', 'converted')->count();
        $totalQtyCount = Quote::sum('quantity');

        return view('admin.quotes.index', compact(
            'quotes',
            'totalQuotesCount',
            'pendingQuotesCount',
            'quotedCount',
            'convertedCount',
            'totalQtyCount'
        ));
    }

    /**
     * Show form for creating a new Quote Inquiry manually in Admin.
     */
    public function create(): View
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $stocks = Stock::orderBy('product_name')->get();
        $suggestedQuoteNumber = Quote::generateUniqueCode();

        return view('admin.quotes.create', compact('customers', 'stocks', 'suggestedQuoteNumber'));
    }

    /**
     * Store a newly created Quote Inquiry in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quote_number' => 'required|string|max:50|unique:quotes,quote_number',
            'customer_id' => 'nullable|exists:customers,id',
            'stock_id' => 'nullable|exists:stocks,id',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'target_price' => 'nullable|numeric|min:0',
            'offered_price' => 'nullable|numeric|min:0',
            'required_date' => 'nullable|date',
            'status' => 'required|string|in:pending,under_review,quoted,approved,rejected,converted',
            'message' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $quote = Quote::create([
            'quote_number' => $validated['quote_number'],
            'customer_id' => $validated['customer_id'] ?? null,
            'stock_id' => $validated['stock_id'] ?? null,
            'user_id' => auth()->id(),
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'target_price' => $validated['target_price'] ?? null,
            'offered_price' => $validated['offered_price'] ?? null,
            'required_date' => $validated['required_date'] ?? null,
            'status' => $validated['status'],
            'message' => $validated['message'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'quoted_at' => !empty($validated['offered_price']) ? now() : null,
        ]);

        return redirect()->route('admin.quotes.index')
            ->with('success', "Quote Request '{$quote->quote_number}' created successfully.");
    }

    /**
     * Display specified quote inquiry details.
     */
    public function show($id): View
    {
        $quote = Quote::with('customer', 'stock', 'user')->findOrFail($id);

        return view('admin.quotes.show', compact('quote'));
    }

    /**
     * Show form for editing an existing Quote Inquiry.
     */
    public function edit($id): View
    {
        $quote = Quote::with('customer', 'stock')->findOrFail($id);
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $stocks = Stock::orderBy('product_name')->get();

        return view('admin.quotes.edit', compact('quote', 'customers', 'stocks'));
    }

    /**
     * Update specified quote inquiry in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $quote = Quote::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'stock_id' => 'nullable|exists:stocks,id',
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'target_price' => 'nullable|numeric|min:0',
            'offered_price' => 'nullable|numeric|min:0',
            'required_date' => 'nullable|date',
            'status' => 'required|string|in:pending,under_review,quoted,approved,rejected,converted',
            'message' => 'nullable|string|max:2000',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $quotedAt = $quote->quoted_at;
        if (!empty($validated['offered_price']) && empty($quotedAt)) {
            $quotedAt = now();
        }

        $quote->update(array_merge($validated, ['quoted_at' => $quotedAt]));

        return redirect()->route('admin.quotes.show', $quote->id)
            ->with('success', "Quote #{$quote->quote_number} updated successfully.");
    }

    /**
     * Update price offer and status.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $quote = Quote::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:pending,under_review,quoted,approved,rejected,converted',
            'offered_price' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'offered_price' => $validated['offered_price'] ?? $quote->offered_price,
            'admin_notes' => $validated['admin_notes'] ?? $quote->admin_notes,
        ];

        if (!empty($validated['offered_price'])) {
            $updateData['quoted_at'] = now();
        }

        $quote->update($updateData);

        return redirect()->back()
            ->with('success', "Quote #{$quote->quote_number} status updated to " . ucfirst(str_replace('_', ' ', $validated['status'])) . ".");
    }

    /**
     * Convert an approved or quoted inquiry directly into a Wholesale Order.
     */
    public function convertToOrder($id): RedirectResponse
    {
        $quote = Quote::findOrFail($id);

        if ($quote->status === 'converted') {
            return back()->with('error', "Quote #{$quote->quote_number} has already been converted to an order.");
        }

        // Find or locate customer
        $customer = $quote->customer;
        if (!$customer) {
            $customer = Customer::where('email', $quote->email)->first();
        }

        if (!$customer) {
            $customer = Customer::create([
                'customer_code' => Customer::generateUniqueCode(),
                'name' => $quote->contact_name,
                'company_name' => $quote->company_name,
                'email' => $quote->email,
                'phone' => $quote->phone,
                'tier' => 'Standard Wholesale',
                'wholesale_discount' => 0.00,
                'credit_limit' => 5000.00,
                'status' => 'active',
                'address' => 'Customer Direct Inquiry Address',
                'city' => 'Phnom Penh',
                'country' => 'Cambodia',
            ]);
        }

        $unitPrice = $quote->offered_price ?? $quote->target_price ?? 0;
        $subtotal = round($unitPrice * $quote->quantity, 2);
        $taxAmount = round($subtotal * 0.05, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        $nextId = (Order::max('id') ?? 0) + 1;
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'order_source' => 'admin',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_terms' => 'Net 30',
            'subtotal' => $subtotal,
            'discount_amount' => 0.00,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'shipping_address' => $customer->address . ', ' . $customer->city,
            'notes' => "Converted from Quote Inquiry #{$quote->quote_number}. Note: {$quote->message}",
            'order_date' => now(),
            'due_date' => now()->addDays(30),
        ]);

        $order->items()->create([
            'stock_id' => $quote->stock_id,
            'product_name' => $quote->product_name,
            'quantity' => $quote->quantity,
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
        ]);

        // Deduct stock if linked
        if ($quote->stock_id) {
            $stock = Stock::find($quote->stock_id);
            if ($stock && $stock->quantity >= $quote->quantity) {
                $stock->decrement('quantity', $quote->quantity);
            }
        }

        $customer->increment('total_orders');
        $customer->increment('total_spent', $totalAmount);

        // Mark quote as converted
        $quote->update([
            'status' => 'converted',
            'admin_notes' => ($quote->admin_notes ? $quote->admin_notes . "\n" : '') . "Converted to B2B Order #{$order->order_number} on " . now()->format('Y-m-d H:i')
        ]);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', "Quote #{$quote->quote_number} successfully converted to B2B Order #{$order->order_number}!");
    }

    /**
     * Remove the specified quote inquiry from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $quote = Quote::findOrFail($id);
        $quoteNum = $quote->quote_number;
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', "Quote Request #{$quoteNum} deleted successfully.");
    }
}
