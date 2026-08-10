<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('admin.auth.register');
    }

    /**
     * Handle an incoming user registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        // Assign default "User" role (frontend customer)
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $user->assignRole($userRole->name);
        }

        // Auto-login and redirect to frontend
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('frontend.home')->with('success', 'Welcome! Your account has been created successfully.');
    }
}
