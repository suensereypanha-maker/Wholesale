<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FormComponentsController extends Controller
{
    /**
     * Display the Form Components Showcase page.
     */
    public function index(): View
    {
        return view('admin.forms.showcase');
    }

    /**
     * Test form validation submit.
     */
    public function testSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'demo_name' => 'required|min:3',
            'demo_email' => 'required|email',
            'demo_role' => 'required',
            'demo_bio' => 'nullable|min:10',
            'demo_terms' => 'accepted',
        ], [
            'demo_terms.accepted' => 'You must accept the terms & conditions to proceed.',
        ]);

        return back()->with('success', 'Form submitted successfully! Validation passed cleanly.');
    }
}
