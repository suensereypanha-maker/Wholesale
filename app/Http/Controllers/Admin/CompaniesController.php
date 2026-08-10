<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CompaniesController extends Controller
{
    /**
     * Display a listing of B2B client companies.
     */
    public function index(Request $request): View
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('industry')) {
            $query->industry($request->input('industry'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $companies = $query->latest()->paginate(10)->withQueryString();

        // Metrics Summary
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $totalCreditLimit = Company::sum('credit_limit');
        $industriesCount = Company::distinct('industry')->whereNotNull('industry')->count('industry');

        $industries = Company::distinct()
            ->whereNotNull('industry')
            ->pluck('industry')
            ->filter();

        return view('admin.companies.index', compact(
            'companies',
            'totalCompanies',
            'activeCompanies',
            'totalCreditLimit',
            'industriesCount',
            'industries'
        ));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create(): View
    {
        $industries = [
            'Electronics & Hardware Wholesale',
            'IT Infrastructure Logistics',
            'Retail Chain Stores',
            'International B2B Trade',
            'Semiconductors & Component Imports',
            'System Integration & Enterprise IT',
            'General Wholesale & Logistics',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Approval',
            'inactive' => 'Inactive / Suspended',
        ];

        // Auto code suggestion
        $nextId = (Company::max('id') ?? 0) + 1;
        $suggestedCode = 'COMP-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        return view('admin.companies.create', compact('industries', 'statuses', 'suggestedCode'));
    }

    /**
     * Store a newly created company in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_code' => 'required|string|max:50|unique:companies,company_code',
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255|unique:companies,email',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'total_employees' => 'nullable|integer|min:1',
            'credit_limit' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $company = Company::create($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', "Company '{$company->name}' ({$company->company_code}) created successfully.");
    }

    /**
     * Display the specified company profile.
     */
    public function show(Company $company): View
    {
        return view('admin.companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified company.
     */
    public function edit(Company $company): View
    {
        $industries = [
            'Electronics & Hardware Wholesale',
            'IT Infrastructure Logistics',
            'Retail Chain Stores',
            'International B2B Trade',
            'Semiconductors & Component Imports',
            'System Integration & Enterprise IT',
            'General Wholesale & Logistics',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Approval',
            'inactive' => 'Inactive / Suspended',
        ];

        return view('admin.companies.edit', compact('company', 'industries', 'statuses'));
    }

    /**
     * Update the specified company in storage.
     */
    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'company_code' => ['required', 'string', 'max:50', Rule::unique('companies')->ignore($company->id)],
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:100',
            'industry' => 'nullable|string|max:100',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('companies')->ignore($company->id)],
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'total_employees' => 'nullable|integer|min:1',
            'credit_limit' => 'required|numeric|min:0',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', "Company '{$company->name}' updated successfully.");
    }

    /**
     * Remove the specified company from storage.
     */
    public function destroy(Company $company): RedirectResponse
    {
        $name = $company->name;
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', "Company '{$name}' deleted successfully.");
    }
}
