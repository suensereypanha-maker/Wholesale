<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = $user ? $user->toArray() : session()->get('frontend_customer', FrontendData::sampleCustomer());
        $orders = session()->get('frontend_orders', FrontendData::sampleOrders());

        // Fetch DB quotes or fallback to sample quotes
        if ($user) {
            $dbQuotes = \App\Models\Quote::with('stock')
                ->where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->latest()
                ->get();
        } else {
            $customerEmail = session()->get('frontend_customer.email', 'info@ccycompany.com');
            $dbQuotes = \App\Models\Quote::with('stock')
                ->where('email', $customerEmail)
                ->orWhereNull('user_id')
                ->latest()
                ->get();
        }

        if ($dbQuotes->isNotEmpty()) {
            $quotes = $dbQuotes->map(function ($q) {
                return [
                    'id' => $q->id,
                    'quote_number' => $q->quote_number,
                    'date' => $q->created_at->format('Y-m-d'),
                    'product_name' => $q->product_name,
                    'quantity' => $q->quantity,
                    'target_price' => $q->target_price,
                    'offered_price' => $q->offered_price,
                    'status' => $q->status,
                    'status_label' => $q->status_label,
                    'status_badge' => $q->status_badge,
                    'message' => $q->message,
                ];
            })->toArray();
        } else {
            $quotes = session()->get('frontend_quotes', FrontendData::sampleQuotes());
        }

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
        $user = Auth::user();
        $customer = $user ? $user->toArray() : session()->get('frontend_customer', FrontendData::sampleCustomer());
        return view('frontend.account.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'tax_number' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($user ? $user->id : 0),
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
        ]);

        if ($user) {
            $user->update($validated);
        } else {
            session()->put('frontend_customer', $validated);
        }

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
