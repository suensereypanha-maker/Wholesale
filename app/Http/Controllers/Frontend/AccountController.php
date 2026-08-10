<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $customer = session()->get('frontend_customer', FrontendData::sampleCustomer());
        $orders = session()->get('frontend_orders', FrontendData::sampleOrders());
        $quotes = session()->get('frontend_quotes', FrontendData::sampleQuotes());

        $totalOrders = count($orders);
        $pendingOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['Pending', 'Confirmed', 'Processing', 'Packed'])));
        $completedOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['Delivered', 'Completed'])));
        $totalPurchase = array_sum(array_column($orders, 'total'));

        return view('frontend.account.index', compact(
            'customer',
            'orders',
            'quotes',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalPurchase'
        ));
    }

    public function profile()
    {
        $customer = session()->get('frontend_customer', FrontendData::sampleCustomer());
        return view('frontend.account.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'tax_number' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
        ]);

        $customer = [
            'id' => 1,
            'company' => $request->company,
            'tax_number' => $request->tax_number,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'zip' => $request->zip,
            'country' => $request->country,
        ];

        session()->put('frontend_customer', $customer);

        return redirect()->route('frontend.account.profile')->with('success', 'Company profile updated successfully.');
    }

    public function wishlist()
    {
        $wishlistIds = session()->get('frontend_wishlist', []);
        $allProducts = FrontendData::products();
        $wishlistProducts = [];

        foreach ($allProducts as $p) {
            if (in_array($p['id'], $wishlistIds)) {
                $wishlistProducts[] = $p;
            }
        }

        return view('frontend.account.wishlist', compact('wishlistProducts'));
    }

    public function addWishlist(Request $request)
    {
        $productId = (int)$request->product_id;
        $wishlist = session()->get('frontend_wishlist', []);

        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            session()->put('frontend_wishlist', $wishlist);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to your wishlist.',
                'count' => count($wishlist)
            ]);
        }

        return redirect()->back()->with('success', 'Product added to your wishlist.');
    }

    public function removeWishlist(Request $request, $id)
    {
        $wishlist = session()->get('frontend_wishlist', []);
        $productId = (int)$id;

        $wishlist = array_values(array_diff($wishlist, [$productId]));
        session()->put('frontend_wishlist', $wishlist);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist.',
                'count' => count($wishlist)
            ]);
        }

        return redirect()->route('frontend.account.wishlist')->with('success', 'Item removed from wishlist.');
    }
}
