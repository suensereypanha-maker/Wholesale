<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Quote;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    /**
     * Display listing of buyer's submitted quotes and their live admin approval status.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $customer = $user ? $user->toArray() : session()->get('frontend_customer', FrontendData::sampleCustomer());

        if ($user) {
            $quotes = Quote::with('stock')
                ->where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->latest()
                ->get();
        } else {
            $customerEmail = session()->get('frontend_customer.email', 'info@ccycompany.com');
            $quotes = Quote::with('stock')
                ->where('email', $customerEmail)
                ->orWhereNull('user_id')
                ->latest()
                ->get();
        }

        // Fallback to sample session quotes if DB is empty
        if ($quotes->isEmpty()) {
            $sampleQuotes = session()->get('frontend_quotes', FrontendData::sampleQuotes());
            $quotes = collect($sampleQuotes)->map(function ($sq) {
                return (object)[
                    'id' => $sq['id'],
                    'quote_number' => $sq['id'],
                    'created_at' => \Carbon\Carbon::parse($sq['date']),
                    'product_name' => $sq['product_name'],
                    'quantity' => $sq['quantity'],
                    'target_price' => $sq['target_price'] ?? null,
                    'offered_price' => isset($sq['offered_price']) ? $sq['offered_price'] : ($sq['status'] === 'Approved' ? ($sq['target_price'] ?? 150) : null),
                    'status' => strtolower(str_replace(' ', '_', $sq['status'])),
                    'status_label' => $sq['status'],
                    'status_badge' => $sq['status'] === 'Approved' ? 'bg-success text-white' : 'bg-warning text-dark',
                    'message' => $sq['message'] ?? '',
                ];
            });
        }

        return view('frontend.quotes.index', compact('customer', 'quotes'));
    }

    /**
     * Form to create a new RFQ (Request for Quote).
     */
    public function create(Request $request)
    {
        $products = FrontendData::products();
        $selectedProduct = null;

        if ($request->filled('product_id')) {
            $selectedProduct = FrontendData::getProductById($request->product_id);
        }

        $customer = session()->get('frontend_customer', FrontendData::sampleCustomer());

        return view('frontend.quotes.create', compact('products', 'selectedProduct', 'customer'));
    }

    /**
     * Store new quote request in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'target_price' => 'nullable|numeric|min:0',
            'required_date' => 'nullable|date',
            'message' => 'nullable|string|max:2000',
            'stock_id' => 'nullable|exists:stocks,id',
        ]);

        $quoteNumber = Quote::generateUniqueCode();
        $user = Auth::user();
        $customer = Customer::where('email', $validated['email'])->first();
        $stock = !empty($validated['stock_id']) ? Stock::find($validated['stock_id']) : null;
        if (!$stock) {
            $stock = Stock::where('product_name', 'like', "%{$validated['product_name']}%")->first();
        }

        $quote = Quote::create([
            'quote_number' => $quoteNumber,
            'user_id' => $user?->id,
            'customer_id' => $customer?->id,
            'stock_id' => $stock?->id,
            'company_name' => $validated['company'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'product_name' => $validated['product_name'],
            'quantity' => $validated['quantity'],
            'target_price' => $validated['target_price'] ?? null,
            'required_date' => $validated['required_date'] ?? null,
            'status' => 'pending',
            'message' => $validated['message'] ?? null,
        ]);

        return redirect()->route('frontend.quotes.index')->with('success', "Wholesale quote request submitted! Reference Quote ID: {$quote->quote_number}");
    }

    /**
     * Customer accepts approved price offer & converts quote to active Wholesale Order.
     */
    public function acceptAndOrder(Request $request, $id)
    {
        $quote = Quote::where('id', $id)->orWhere('quote_number', $id)->first();

        if (!$quote) {
            // Handle session sample quote conversion
            $sampleQuotes = session()->get('frontend_quotes', FrontendData::sampleQuotes());
            $match = null;
            foreach ($sampleQuotes as $sq) {
                if ($sq['id'] == $id) {
                    $match = $sq;
                    break;
                }
            }

            $quoteNumber = $match ? $match['id'] : $id;
            $productName = $match ? $match['product_name'] : 'Wholesale Product';
            $qty = $match ? (int)$match['quantity'] : 50;
            $unitPrice = $match && isset($match['target_price']) ? (float)$match['target_price'] : 150.00;

            // Generate order in session
            $orderId = 'ORD-' . date('Y') . '-' . rand(1000, 9999);
            $subtotal = round($unitPrice * $qty, 2);
            $tax = round($subtotal * 0.05, 2);
            $total = $subtotal + $tax;

            $newOrder = [
                'id' => $orderId,
                'date' => date('Y-m-d'),
                'subtotal' => $subtotal,
                'shipping' => 0.00,
                'tax' => $tax,
                'total' => $total,
                'status' => 'Pending',
                'payment_status' => 'Unpaid',
                'order_source' => 'frontend',
                'items' => [
                    [
                        'name' => $productName,
                        'sku' => 'SKU-QUOTE-' . rand(100, 999),
                        'quantity' => $qty,
                        'price' => $unitPrice,
                        'total' => $subtotal,
                    ]
                ]
            ];

            $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
            array_unshift($orders, $newOrder);
            session()->put('frontend_orders', $orders);

            return redirect()->route('frontend.orders.show', $orderId)->with('success', "Quote {$quoteNumber} accepted! Wholesale Order #{$orderId} created at approved price of $" . number_format($unitPrice, 2) . " per unit.");
        }

        if ($quote->status === 'converted') {
            return redirect()->back()->with('error', "Quote #{$quote->quote_number} has already been converted to an active order.");
        }

        // Determine agreed unit price
        $unitPrice = $quote->offered_price ?? $quote->target_price ?? 0;
        $subtotal = round($unitPrice * $quote->quantity, 2);
        $taxAmount = round($subtotal * 0.05, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        // Find or create customer
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
                'address' => 'Registered Customer Address',
                'city' => 'Phnom Penh',
                'country' => 'Cambodia',
            ]);
        }

        $nextId = (Order::max('id') ?? 0) + 1;
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $customer->id,
            'user_id' => Auth::id() ?? $quote->user_id,
            'order_source' => 'frontend',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_terms' => 'Net 30',
            'subtotal' => $subtotal,
            'discount_amount' => 0.00,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'shipping_address' => $customer->address . ', ' . $customer->city,
            'notes' => "Placed from Approved Quote Inquiry #{$quote->quote_number}. Agreed Unit Price: $" . number_format($unitPrice, 2),
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

        // Decrement inventory stock if linked
        if ($quote->stock_id) {
            $stock = Stock::find($quote->stock_id);
            if ($stock && $stock->quantity >= $quote->quantity) {
                $stock->decrement('quantity', $quote->quantity);
            }
        }

        $customer->increment('total_orders');
        $customer->increment('total_spent', $totalAmount);

        // Update quote status to converted
        $quote->update([
            'status' => 'converted',
            'admin_notes' => ($quote->admin_notes ? $quote->admin_notes . "\n" : '') . "Accepted & Converted by Customer into Order #{$order->order_number} on " . now()->format('Y-m-d H:i')
        ]);

        return redirect()->route('frontend.orders.show', $order->id)
            ->with('success', "Quote #{$quote->quote_number} accepted! Wholesale Order #{$order->order_number} has been created at the approved price of $" . number_format($unitPrice, 2) . "/unit.");
    }
}
