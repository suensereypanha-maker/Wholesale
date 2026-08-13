<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of supplier payments.
     */
    public function index(Request $request): View
    {
        $query = SupplierPayment::with('supplier');

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->status($request->input('status'));
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        $payments = $query->latest('due_date')->paginate(12)->withQueryString();

        // Calculate KPI Metrics
        $totalPayable = SupplierPayment::sum('total_amount');
        $totalPaid = SupplierPayment::sum('paid_amount');
        $totalUnpaid = SupplierPayment::where('payment_status', '!=', 'paid')->sum('due_amount');
        
        $paidCount = SupplierPayment::where('payment_status', 'paid')->count();
        $unpaidCount = SupplierPayment::where('payment_status', 'unpaid')->count();
        $partialCount = SupplierPayment::where('payment_status', 'partial')->count();
        $overdueCount = SupplierPayment::where('payment_status', '!=', 'paid')
            ->where('due_date', '<', now()->toDateString())
            ->count();

        $suppliers = Supplier::orderBy('name')->get();
        $dbPaymentMethods = PaymentMethod::active()->orderBy('name')->pluck('name')->toArray();
        $paymentMethods = !empty($dbPaymentMethods) ? $dbPaymentMethods : ['Bank Transfer', 'Cash', 'Wire Transfer', 'Credit Line', 'Cheque', 'Online Gateway'];

        return view('admin.supplier_payments.index', compact(
            'payments',
            'totalPayable',
            'totalPaid',
            'totalUnpaid',
            'paidCount',
            'unpaidCount',
            'partialCount',
            'overdueCount',
            'suppliers',
            'paymentMethods'
        ));
    }

    /**
     * Show the form for creating a new supplier payment record.
     */
    public function create(): View
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        if ($suppliers->isEmpty()) {
            $suppliers = Supplier::orderBy('name')->get();
        }

        $maxId = SupplierPayment::max('id') ?? 0;
        $suggestedCode = 'PAY-' . date('Y') . '-' . str_pad($maxId + 1001, 4, '0', STR_PAD_LEFT);
        $suggestedInvoice = 'INV-SUP-' . rand(10000, 99999);

        $dbPaymentMethods = PaymentMethod::active()->orderBy('name')->pluck('name')->toArray();
        $paymentMethods = !empty($dbPaymentMethods) ? $dbPaymentMethods : [
            'Bank Transfer',
            'Cash',
            'Wire Transfer',
            'Credit Line',
            'Cheque',
            'Online Gateway',
        ];

        return view('admin.supplier_payments.create', compact('suppliers', 'suggestedCode', 'suggestedInvoice', 'paymentMethods'));
    }

    /**
     * Store a newly created supplier payment record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_code' => 'required|string|max:50|unique:supplier_payments,payment_code',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:purchase_date',
            'total_amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $totalAmount = (float) $validated['total_amount'];
        $paidAmount = (float) ($validated['paid_amount'] ?? 0);
        
        if ($paidAmount > $totalAmount) {
            $paidAmount = $totalAmount;
        }

        $dueAmount = max(0, $totalAmount - $paidAmount);

        if ($dueAmount == 0 && $totalAmount > 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        SupplierPayment::create([
            'payment_code' => $validated['payment_code'],
            'supplier_id' => $validated['supplier_id'],
            'invoice_number' => $validated['invoice_number'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'due_date' => $validated['due_date'],
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $status,
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_date' => $paidAmount > 0 ? ($validated['payment_date'] ?? now()->toDateString()) : null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.supplier-payments.index')
            ->with('success', "Supplier payment record {$validated['payment_code']} created successfully.");
    }

    /**
     * Display the specified supplier payment invoice details.
     */
    public function show(SupplierPayment $supplierPayment): View
    {
        $supplierPayment->load(['supplier', 'creator']);
        return view('admin.supplier_payments.show', compact('supplierPayment'));
    }

    /**
     * Show the form for editing the specified supplier payment record.
     */
    public function edit(SupplierPayment $supplierPayment): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $dbPaymentMethods = PaymentMethod::active()->orderBy('name')->pluck('name')->toArray();
        $paymentMethods = !empty($dbPaymentMethods) ? $dbPaymentMethods : [
            'Bank Transfer',
            'Cash',
            'Wire Transfer',
            'Credit Line',
            'Cheque',
            'Online Gateway',
        ];

        return view('admin.supplier_payments.edit', compact('supplierPayment', 'suppliers', 'paymentMethods'));
    }

    /**
     * Update the specified supplier payment record in storage.
     */
    public function update(Request $request, SupplierPayment $supplierPayment): RedirectResponse
    {
        $validated = $request->validate([
            'payment_code' => ['required', 'string', 'max:50', Rule::unique('supplier_payments')->ignore($supplierPayment->id)],
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:purchase_date',
            'total_amount' => 'required|numeric|min:0.01',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:100',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
        ]);

        $totalAmount = (float) $validated['total_amount'];
        $paidAmount = (float) ($validated['paid_amount'] ?? 0);

        if ($paidAmount > $totalAmount) {
            $paidAmount = $totalAmount;
        }

        $dueAmount = max(0, $totalAmount - $paidAmount);

        if ($dueAmount == 0 && $totalAmount > 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        $supplierPayment->update([
            'payment_code' => $validated['payment_code'],
            'supplier_id' => $validated['supplier_id'],
            'invoice_number' => $validated['invoice_number'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'due_date' => $validated['due_date'],
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'payment_status' => $status,
            'payment_method' => $validated['payment_method'] ?? null,
            'payment_date' => $paidAmount > 0 ? ($validated['payment_date'] ?? $supplierPayment->payment_date ?? now()->toDateString()) : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.supplier-payments.index')
            ->with('success', "Supplier payment {$supplierPayment->payment_code} updated successfully.");
    }

    /**
     * Record a new payment towards an existing supplier bill.
     */
    public function recordPayment(Request $request, SupplierPayment $supplierPayment): RedirectResponse
    {
        $validated = $request->validate([
            'amount_to_pay' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:100',
            'payment_date' => 'required|date',
            'payment_notes' => 'nullable|string|max:1000',
        ]);

        $amountToPay = (float) $validated['amount_to_pay'];
        $newPaidAmount = $supplierPayment->paid_amount + $amountToPay;

        if ($newPaidAmount >= $supplierPayment->total_amount) {
            $newPaidAmount = $supplierPayment->total_amount;
            $newDueAmount = 0;
            $status = 'paid';
        } else {
            $newDueAmount = $supplierPayment->total_amount - $newPaidAmount;
            $status = 'partial';
        }

        $existingNotes = $supplierPayment->notes ? $supplierPayment->notes . "\n" : "";
        $noteAppend = "[" . now()->format('Y-m-d H:i') . "] Paid $" . number_format($amountToPay, 2) . " via " . $validated['payment_method'];
        if (!empty($validated['payment_notes'])) {
            $noteAppend .= ": " . $validated['payment_notes'];
        }

        $supplierPayment->update([
            'paid_amount' => $newPaidAmount,
            'due_amount' => $newDueAmount,
            'payment_status' => $status,
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'notes' => $existingNotes . $noteAppend,
        ]);

        return redirect()->route('admin.supplier-payments.index')
            ->with('success', "Successfully recorded payment of $" . number_format($amountToPay, 2) . " for {$supplierPayment->payment_code}.");
    }

    /**
     * Remove the specified supplier payment record.
     */
    public function destroy(SupplierPayment $supplierPayment): RedirectResponse
    {
        $code = $supplierPayment->payment_code;
        $supplierPayment->delete();

        return redirect()->route('admin.supplier-payments.index')
            ->with('success', "Supplier payment record {$code} deleted successfully.");
    }
}
