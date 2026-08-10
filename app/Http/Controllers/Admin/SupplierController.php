<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request): View
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        // Calculate summary metrics
        $totalSuppliers = Supplier::count();
        $activeSuppliers = Supplier::where('status', 'active')->count();
        $pendingSuppliers = Supplier::where('status', 'pending')->count();
        $categoriesCount = Supplier::distinct('category')->whereNotNull('category')->count('category');

        $categories = Supplier::distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->filter();

        return view('admin.suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'activeSuppliers',
            'pendingSuppliers',
            'categoriesCount',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create(): View
    {
        $categories = \App\Models\Category::active()->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'CPUs & Processors',
                'RAM & Memory Modules',
                'SSDs & Storage Drives',
                'Graphics Cards & GPUs',
                'Motherboards & Mainboards',
                'Laptop Parts & Components',
                'Electronics & Hardware',
            ];
        }

        $paymentTermsOptions = [
            'Net 15',
            'Net 30',
            'Net 60',
            'Cash on Delivery',
            'Advance',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Audit',
            'inactive' => 'Inactive',
        ];

        // Auto-generate suggest code
        $nextId = (Supplier::max('id') ?? 0) + 1;
        $suggestedCode = 'SUP-' . str_pad($nextId + 1000, 4, '0', STR_PAD_LEFT);

        return view('admin.suppliers.create', compact('categories', 'paymentTermsOptions', 'statuses', 'suggestedCode'));
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:suppliers,code',
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'payment_terms' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' ({$supplier->code}) created successfully.");
    }

    /**
     * Display the specified supplier profile.
     */
    public function show(Supplier $supplier): View
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier): View
    {
        $categories = \App\Models\Category::active()->orderBy('name')->pluck('name')->toArray();
        if (empty($categories)) {
            $categories = [
                'CPUs & Processors',
                'RAM & Memory Modules',
                'SSDs & Storage Drives',
                'Graphics Cards & GPUs',
                'Motherboards & Mainboards',
                'Laptop Parts & Components',
                'Electronics & Hardware',
            ];
        }

        $paymentTermsOptions = [
            'Net 15',
            'Net 30',
            'Net 60',
            'Cash on Delivery',
            'Advance',
        ];

        $statuses = [
            'active' => 'Active',
            'pending' => 'Pending Audit',
            'inactive' => 'Inactive',
        ];

        return view('admin.suppliers.edit', compact('supplier', 'categories', 'paymentTermsOptions', 'statuses'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('suppliers')->ignore($supplier->id)],
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('suppliers')->ignore($supplier->id)],
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'payment_terms' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'status' => 'required|string|in:active,pending,inactive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' updated successfully.");
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplierName = $supplier->name;
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', "Supplier '{$supplierName}' deleted successfully.");
    }
}
