<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     */
    public function index(Request $request): View
    {
        $query = PaymentMethod::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $paymentMethods = $query->latest()->paginate(10)->withQueryString();

        // Metrics
        $totalMethods = PaymentMethod::count();
        $activeMethods = PaymentMethod::where('status', 'active')->count();
        $bankMethods = PaymentMethod::where('type', 'bank')->count();
        $digitalMethods = PaymentMethod::whereIn('type', ['digital', 'cash', 'credit'])->count();

        $types = [
            'bank' => 'Bank Account / Transfer',
            'cash' => 'Cash',
            'digital' => 'Digital Wallet / QR',
            'credit' => 'Credit Line',
            'other' => 'Other Method',
        ];

        return view('admin.payment_methods.index', compact(
            'paymentMethods',
            'totalMethods',
            'activeMethods',
            'bankMethods',
            'digitalMethods',
            'types'
        ));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(): View
    {
        $maxId = PaymentMethod::max('id') ?? 0;
        $suggestedCode = 'PM-' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);

        $types = [
            'bank' => 'Bank Account / Transfer',
            'cash' => 'Cash',
            'digital' => 'Digital Wallet / QR (ABA, Wings, etc.)',
            'credit' => 'Credit Line / Net Terms',
            'other' => 'Other Gateway / Cheque',
        ];

        return view('admin.payment_methods.create', compact('suggestedCode', 'types'));
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank,cash,digital,credit,other',
            'account_number' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $paymentMethod = PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Payment method '{$paymentMethod->name}' ({$paymentMethod->code}) created successfully.");
    }

    /**
     * Display the specified payment method.
     */
    public function show(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        $types = [
            'bank' => 'Bank Account / Transfer',
            'cash' => 'Cash',
            'digital' => 'Digital Wallet / QR (ABA, Wings, etc.)',
            'credit' => 'Credit Line / Net Terms',
            'other' => 'Other Gateway / Cheque',
        ];

        return view('admin.payment_methods.edit', compact('paymentMethod', 'types'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('payment_methods')->ignore($paymentMethod->id)],
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bank,cash,digital,credit,other',
            'account_number' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
            'notes' => 'nullable|string|max:1000',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Payment method '{$paymentMethod->name}' updated successfully.");
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $name = $paymentMethod->name;
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Payment method '{$name}' deleted successfully.");
    }
}
