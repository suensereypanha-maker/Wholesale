<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
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

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'product_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'target_price' => 'nullable|numeric',
            'required_date' => 'required|date',
            'message' => 'nullable|string',
        ]);

        $quoteId = 'QT-' . date('Ymd') . '-' . str_pad(rand(10, 999), 3, '0', STR_PAD_LEFT);

        $newQuote = [
            'id' => $quoteId,
            'date' => date('Y-m-d'),
            'company' => $request->company,
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'product_name' => $request->product_name,
            'quantity' => (int)$request->quantity,
            'target_price' => $request->target_price ? (float)$request->target_price : null,
            'required_date' => $request->required_date,
            'status' => 'Under Review',
            'message' => $request->message ?? '',
        ];

        $quotes = session()->get('frontend_quotes', FrontendData::sampleQuotes());
        array_unshift($quotes, $newQuote);
        session()->put('frontend_quotes', $quotes);

        return redirect()->route('frontend.account')->with('success', "Wholesale quote request submitted! Reference Quote ID: {$quoteId}");
    }
}
