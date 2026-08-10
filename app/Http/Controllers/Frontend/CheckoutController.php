<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

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

        $customer = session()->get('frontend_customer', FrontendData::sampleCustomer());

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

        $items = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = FrontendData::getProductById($id);
            if (!$product) continue;

            $quantity = max(1, (int)$item['quantity']);
            $pricing = FrontendData::getWholesalePrice($product, $quantity);

            $subtotal += $pricing['subtotal'];
            $items[] = [
                'product_id' => $product['id'],
                'sku' => $product['sku'],
                'name' => $product['name'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'price' => $pricing['unitPrice'],
                'subtotal' => $pricing['subtotal'],
            ];
        }

        $tax = $subtotal * 0.10;
        $shipping = $subtotal > 2000 ? 0 : 75;
        $total = $subtotal + $tax + $shipping;

        $orderId = 'ORD-' . date('Ymd') . '-' . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);

        $newOrder = [
            'id' => $orderId,
            'date' => date('Y-m-d'),
            'company' => $request->company,
            'contact_person' => $request->contact_person,
            'email' => $request->email,
            'phone' => $request->phone,
            'tax_number' => $request->tax_number,
            'status' => 'Confirmed',
            'payment_method' => $request->payment_method,
            'delivery_note' => $request->delivery_note ?? '',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
            'items' => $items,
            'shipping_address' => "{$request->address}, {$request->city}, {$request->province}",
        ];

        // Store in session
        $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
        array_unshift($orders, $newOrder);
        session()->put('frontend_orders', $orders);

        // Clear cart
        session()->forget('frontend_cart');

        return redirect()->route('frontend.orders.show', $orderId)->with('success', "Order {$orderId} placed successfully! Thank you for your business.");
    }
}
