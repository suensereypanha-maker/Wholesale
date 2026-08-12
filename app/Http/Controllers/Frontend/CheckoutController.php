<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('frontend_cart', []);

        if (empty($cart)) {
            return redirect()->route('frontend.products.index')->with('warning', 'Your cart is empty. Please select products to purchase.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = FrontendData::getProductById($id);
            if (!$product) continue;

            $quantity = max(1, (int)$item['quantity']);
            $pricing = FrontendData::getWholesalePrice($product, $quantity);

            $subtotal += $pricing['subtotal'];
            $cartItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $pricing['unitPrice'],
                'subtotal' => $pricing['subtotal'],
            ];
        }

        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 2000 ? 0 : 75;
        $grandTotal = $subtotal + $tax + $shipping;

        $user = Auth::user();
        if ($user) {
            $customer = [
                'company' => $user->company ?? '',
                'tax_number' => $user->tax_number ?? '',
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'city' => $user->city ?? '',
                'province' => $user->province ?? '',
            ];
        } else {
            $customer = session()->get('frontend_customer', FrontendData::sampleCustomer());
        }

        return view('frontend.checkout.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'grandTotal', 'customer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'tax_number' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('frontend_cart', []);

        if (empty($cart)) {
            return redirect()->route('frontend.products.index')->with('error', 'Your cart is empty.');
        }

        return DB::transaction(function () use ($request, $cart) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($cart as $id => $item) {
                $product = FrontendData::getProductById($id);
                if (!$product) continue;

                $quantity = max(1, (int)$item['quantity']);
                $pricing = FrontendData::getWholesalePrice($product, $quantity);

                $subtotal += $pricing['subtotal'];

                // Attempt to locate DB Stock record if available
                $stock = Stock::where('id', $id)->orWhere('sku', $product['sku'] ?? '')->first();

                $itemsData[] = [
                    'stock_id' => $stock ? $stock->id : null,
                    'product_name' => $product['name'],
                    'quantity' => $quantity,
                    'unit_price' => $pricing['unitPrice'],
                    'subtotal' => $pricing['subtotal'],
                ];
            }

            $tax = round($subtotal * 0.10, 2);
            $shipping = $subtotal > 2000 ? 0 : 75;
            $total = round($subtotal + $tax + $shipping, 2);

            // Find or create Customer record
            $user = Auth::user();
            $customer = Customer::where('email', $request->email)->first();

            if (!$customer) {
                $customer = Customer::create([
                    'customer_code' => Customer::generateUniqueCode(),
                    'email' => $request->email,
                    'name' => $request->contact_person,
                    'company_name' => $request->company,
                    'phone' => $request->phone,
                    'tier' => $user->tier ?? 'Standard Wholesale',
                    'wholesale_discount' => $user->wholesale_discount ?? 0.00,
                    'credit_limit' => $user->credit_limit ?? 5000.00,
                    'total_spent' => 0.00,
                    'total_orders' => 0,
                    'payment_terms' => $request->payment_method,
                    'tax_id' => $request->tax_number,
                    'address' => $request->address,
                    'city' => $request->city,
                    'country' => 'Cambodia',
                    'status' => 'active',
                ]);
            }

            // Generate unique Order Number
            $nextId = (Order::max('id') ?? 0) + 1;
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

            // Create Order in DB
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_terms' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount_amount' => 0.00,
                'tax_amount' => $tax,
                'total_amount' => $total,
                'shipping_address' => "{$request->address}, {$request->city}, {$request->province}",
                'notes' => $request->delivery_note ?? '',
                'order_date' => now(),
                'due_date' => now()->addDays(30),
            ]);

            // Save Order Items and adjust stock
            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);

                if (!empty($itemData['stock_id'])) {
                    $stock = Stock::find($itemData['stock_id']);
                    if ($stock && $stock->quantity >= $itemData['quantity']) {
                        $stock->decrement('quantity', $itemData['quantity']);
                    }
                }
            }

            // Update customer totals
            $customer->increment('total_orders');
            $customer->increment('total_spent', $total);

            // Also keep session updated for fallback rendering
            $newOrderSession = [
                'id' => $order->order_number,
                'db_id' => $order->id,
                'date' => date('Y-m-d'),
                'company' => $request->company,
                'contact_person' => $request->contact_person,
                'email' => $request->email,
                'phone' => $request->phone,
                'tax_number' => $request->tax_number,
                'status' => 'Pending',
                'payment_method' => $request->payment_method,
                'delivery_note' => $request->delivery_note ?? '',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'tax' => $tax,
                'total' => $total,
                'shipping_address' => "{$request->address}, {$request->city}, {$request->province}",
            ];

            $orders = session()->get('frontend_orders', []);
            array_unshift($orders, $newOrderSession);
            session()->put('frontend_orders', $orders);

            // Clear cart
            session()->forget('frontend_cart');

            return redirect()->route('frontend.orders.show', $order->id)->with('success', "Order #{$order->order_number} placed successfully! Thank you for your business.");
        });
    }
}

