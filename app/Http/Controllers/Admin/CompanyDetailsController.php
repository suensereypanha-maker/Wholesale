<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyDetailsController extends Controller
{
    /**
     * Display the platform company details profile.
     */
    public function index(): View
    {
        $companyDetail = CompanyDetail::first() ?? new CompanyDetail();

        return view('admin.company_details.index', compact('companyDetail'));
    }

    /**
     * Show the form for editing platform company details.
     */
    public function edit(): View
    {
        $companyDetail = CompanyDetail::first() ?? new CompanyDetail();

        $currencies = [
            'USD ($)' => 'USD ($) - US Dollar',
            'EUR (€)' => 'EUR (€) - Euro',
            'GBP (£)' => 'GBP (£) - British Pound',
            'JPY (¥)' => 'JPY (¥) - Japanese Yen',
        ];

        return view('admin.company_details.edit', compact('companyDetail', 'currencies'));
    }

    /**
     * Update platform company details.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'support_email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'swift_code' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:100',
            'currency' => 'required|string|max:50',
            'timezone' => 'required|string|max:100',
            'description' => 'nullable|string|max:2000',
        ]);

        $companyDetail = CompanyDetail::first();

        if ($companyDetail) {
            $companyDetail->update($validated);
        } else {
            CompanyDetail::create($validated);
        }

        return redirect()->route('admin.company-details.index')
            ->with('success', 'Platform company details and bank wire information updated successfully.');
    }
}
