<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Data\FrontendData;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.auth.login');
    }

    public function storeLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mock login verification
        $customer = FrontendData::sampleCustomer();
        $customer['email'] = $request->email;

        session()->put('frontend_customer', $customer);

        return redirect()->route('frontend.account')->with('success', 'Welcome back, ' . $customer['name'] . '!');
    }

    public function register()
    {
        return view('frontend.auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'tax_number' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $newCustomer = [
            'id' => rand(10, 999),
            'company' => $request->company,
            'tax_number' => $request->tax_number ?? 'VAT-PENDING',
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => 'Corporate Address Provided',
            'city' => 'Business City',
            'province' => 'State',
            'zip' => '10001',
            'country' => 'United States',
        ];

        session()->put('frontend_customer', $newCustomer);

        return redirect()->route('frontend.account')->with('success', 'B2B Account registered successfully!');
    }

    public function logout()
    {
        session()->forget('frontend_customer');
        return redirect()->route('frontend.home')->with('success', 'You have been logged out.');
    }
}
