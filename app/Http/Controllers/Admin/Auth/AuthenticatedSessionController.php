<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to($this->getRedirectPath(Auth::user()));
        }

        return view('admin.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended($this->getRedirectPath(Auth::user()));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    /**
     * Determine redirect path based on user role.
     * Users with only the "User" role go to the frontend.
     * Admin / Manager / Staff / Super Admin go to the admin dashboard.
     */
    protected function getRedirectPath($user): string
    {
        $adminRoles = ['Super Admin', 'Admin', 'Manager', 'Staff'];

        if ($user->hasRole($adminRoles)) {
            return route('admin.dashboard', absolute: false);
        }

        // Default "User" role → frontend
        return route('frontend.home', absolute: false);
    }
}

