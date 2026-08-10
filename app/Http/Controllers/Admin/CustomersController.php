<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CustomersController extends Controller
{
    /**
     * Display a listing of wholesale customers.
     */
    public function index(Request $request): View
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('tier')) {
            $query->tier($request->input('tier'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        // Summary KPI Metrics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'active')->count();
        $totalWholesaleSpent = Customer::sum('total_spent');
        $topTierCount = Customer::whereIn('tier', ['VIP Platinum', 'Wholesale Gold'])->count();

        $tiers = [
            'VIP Platinum',
            'Wholesale Gold',
            'Bulk Silver',
            'Standard Wholesale',
        ];

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'totalWholesaleSpent',
            'topTierCount',
            'tiers'
        ));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        $tiers = [
            'VIP Platinum' => 'VIP Platinum (20%+ Discount)',
            'Wholesale Gold' => 'Wholesale Gold (12-15% Discount)',
            'Bulk Silver' => 'Bulk Silver (8-10% Discount)',
            'Standard Wholesale' => 'Standard Wholesale (5% Discount)',
        ];

        $paymentTerms = [
            'Net 15',
            'Net 30',
            'Net 60',
            'Cash on Delivery',
            'Prepaid',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Approval',
            'inactive' => 'Inactive / Suspended',
        ];

        // Auto-generate suggested customer code
        $nextId = (Customer::max('id') ?? 0) + 1;
        $suggestedCode = 'CUST-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        return view('admin.customers.create', compact('tiers', 'paymentTerms', 'statuses', 'suggestedCode'));
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_code' => 'required|string|max:50|unique:customers,customer_code',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:50',
            'tier' => 'required|string|max:100',
            'wholesale_discount' => 'required|numeric|min:0|max:100',
            'credit_limit' => 'required|numeric|min:0',
            'total_spent' => 'nullable|numeric|min:0',
            'total_orders' => 'nullable|integer|min:0',
            'payment_terms' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $validated['total_spent'] = $validated['total_spent'] ?? 0.00;
        $validated['total_orders'] = $validated['total_orders'] ?? 0;

        $customer = Customer::create($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', "Wholesale Customer '{$customer->name}' ({$customer->customer_code}) created successfully.");
    }

    /**
     * Display the specified customer profile.
     */
    public function show(Customer $customer): View
    {
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        $tiers = [
            'VIP Platinum' => 'VIP Platinum (20%+ Discount)',
            'Wholesale Gold' => 'Wholesale Gold (12-15% Discount)',
            'Bulk Silver' => 'Bulk Silver (8-10% Discount)',
            'Standard Wholesale' => 'Standard Wholesale (5% Discount)',
        ];

        $paymentTerms = [
            'Net 15',
            'Net 30',
            'Net 60',
            'Cash on Delivery',
            'Prepaid',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Approval',
            'inactive' => 'Inactive / Suspended',
        ];

        return view('admin.customers.edit', compact('customer', 'tiers', 'paymentTerms', 'statuses'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'customer_code' => ['required', 'string', 'max:50', Rule::unique('customers')->ignore($customer->id)],
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'nullable|string|max:50',
            'tier' => 'required|string|max:100',
            'wholesale_discount' => 'required|numeric|min:0|max:100',
            'credit_limit' => 'required|numeric|min:0',
            'total_spent' => 'nullable|numeric|min:0',
            'total_orders' => 'nullable|integer|min:0',
            'payment_terms' => 'required|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', "Wholesale Customer '{$customer->name}' updated successfully.");
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $name = $customer->name;
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', "Wholesale Customer '{$name}' deleted successfully.");
    }
}
