<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.auth.login');
    }

    public function storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your B2B account registration is currently pending administrator approval.',
                ])->onlyInput('email');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your B2B account registration has been rejected or suspended. Please contact support.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->route('frontend.account')->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register()
    {
        return view('frontend.auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'tax_number' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'company' => $request->company,
            'tax_number' => $request->filled('tax_number') ? $request->tax_number : null,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address ?? 'Corporate Address Provided',
            'city' => $request->city ?? 'Business City',
            'province' => $request->province ?? 'State',
            'zip' => $request->zip ?? '10001',
            'country' => $request->country ?? 'United States',
            'tier' => 'Standard Wholesale',
            'credit_limit' => 0.00,
            'wholesale_discount' => 0.00,
            'status' => 'pending',
        ]);

        // Assign default User role
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->assignRole($userRole->name);
        }

        return redirect()->route('frontend.login')->with('success', 'Your B2B commercial account application has been submitted successfully! Your account is currently pending administrator approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.home')->with('success', 'You have been logged out.');
    }
}
