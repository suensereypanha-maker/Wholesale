<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('frontend_cart', []);
        $cartItems = [];
        $subtotal = 0;
        $totalSavings = 0;

        foreach ($cart as $id => $item) {
            $product = FrontendData::getProductById($id);
            if (!$product) continue;

            $quantity = max(1, (int)$item['quantity']);
            $pricing = FrontendData::getWholesalePrice($product, $quantity);

            $unitPrice = $pricing['unitPrice'];
            $itemSubtotal = $pricing['subtotal'];

            $subtotal += $itemSubtotal;
            $totalSavings += $pricing['savings'];

            $cartItems[] = [
                'product_id' => $product['id'],
                'sku' => $product['sku'],
                'name' => $product['name'],
                'image' => $product['image'],
                'moq' => $product['moq'],
                'stock' => $product['stock'],
                'quantity' => $quantity,
                'base_price' => $product['price'],
                'unit_price' => $unitPrice,
                'subtotal' => $itemSubtotal,
                'savings' => $pricing['savings'],
                'wholesalePrices' => $product['wholesalePrices'],
            ];
        }

        $estimatedTax = $subtotal * 0.10; // 10% B2B estimated tax
        $estimatedShipping = $subtotal > 2000 ? 0 : 75; // Free shipping over $2000
        $grandTotal = $subtotal + $estimatedTax + $estimatedShipping;

        return view('frontend.cart.index', compact(
            'cartItems',
            'subtotal',
            'totalSavings',
            'estimatedTax',
            'estimatedShipping',
            'grandTotal'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = FrontendData::getProductById($request->product_id);

        if (!$product) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
            }
            return redirect()->back()->with('error', 'Product not found.');
        }

        $quantity = (int) $request->quantity;
        $moq = $product['moq'] ?? 1;

        if ($quantity < $moq) {
            $msg = "Minimum Order Quantity (MOQ) for {$product['name']} is {$moq} units.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $cart = session()->get('frontend_cart', []);
        $productId = $product['id'];

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }

        session()->put('frontend_cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Added {$quantity}x {$product['name']} to wholesale cart!",
                'cart_count' => $cartCount
            ]);
        }

        return redirect()->route('frontend.cart.index')->with('success', 'Product added to wholesale cart.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = FrontendData::getProductById($request->product_id);
        $cart = session()->get('frontend_cart', []);

        if (isset($cart[$request->product_id])) {
            $moq = $product['moq'] ?? 1;
            if ($request->quantity < $moq) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => "Minimum order requirement is {$moq} units."], 422);
                }
                return redirect()->back()->with('error', "Minimum order requirement is {$moq} units.");
            }

            $cart[$request->product_id]['quantity'] = (int) $request->quantity;
            session()->put('frontend_cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cart updated successfully.']);
        }

        return redirect()->route('frontend.cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('frontend_cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('frontend_cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Item removed from cart.']);
        }

        return redirect()->route('frontend.cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear(Request $request)
    {
        session()->forget('frontend_cart');

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cart cleared.']);
        }

        return redirect()->route('frontend.cart.index')->with('success', 'Cart cleared successfully.');
    }
}
