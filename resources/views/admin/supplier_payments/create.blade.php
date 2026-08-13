@extends('admin.layout.app')

@section('title', 'Create Supplier Payment Record')

@section('content')
<div class="space-y-6 w-full max-w-4xl mx-auto">

    <!-- Header Navigation Bar -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.supplier-payments.index') }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Create Supplier Payment Record</h1>
                <p class="text-xs text-slate-500">Register a new purchase invoice or record payment obligation to a supplier</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <x-forms.form action="{{ route('admin.supplier-payments.store') }}" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Payment Reference Code -->
                <div>
                    <x-forms.input 
                        name="payment_code" 
                        label="Payment Ref Code *" 
                        :value="old('payment_code', $suggestedCode)" 
                        required 
                        placeholder="e.g. PAY-2026-0001"
                    />
                </div>

                <!-- Supplier Selection -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Select Supplier *</label>
                    <select name="supplier_id" required class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3 bg-white text-slate-900 shadow-2xs">
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }} @if($sup->company_name) ({{ $sup->company_name }}) @endif - {{ $sup->payment_terms }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Number -->
                <div>
                    <x-forms.input 
                        name="invoice_number" 
                        label="Supplier Invoice / PO Reference #" 
                        :value="old('invoice_number', $suggestedInvoice)" 
                        placeholder="e.g. INV-SUP-88231"
                    />
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Payment Method</label>
                    <select name="payment_method" class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3 bg-white text-slate-900 shadow-2xs">
                        <option value="">-- Select Payment Method --</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}" {{ old('payment_method') == $method ? 'selected' : '' }}>{{ $method }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Purchase Date -->
                <div>
                    <x-forms.input 
                        type="date" 
                        name="purchase_date" 
                        label="Purchase / Invoice Date *" 
                        :value="old('purchase_date', date('Y-m-d'))" 
                        required
                    />
                </div>

                <!-- Due Date -->
                <div>
                    <x-forms.input 
                        type="date" 
                        name="due_date" 
                        label="Payment Due Date *" 
                        :value="old('due_date', date('Y-m-d', strtotime('+30 days')))" 
                        required
                    />
                </div>

                <!-- Total Amount -->
                <div>
                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        min="0.01"
                        name="total_amount" 
                        label="Total Payable Bill Amount ($) *" 
                        :value="old('total_amount')" 
                        required
                        placeholder="0.00"
                    />
                </div>

                <!-- Amount Paid So Far -->
                <div>
                    <x-forms.input 
                        type="number" 
                        step="0.01" 
                        min="0"
                        name="paid_amount" 
                        label="Amount Paid So Far ($)" 
                        :value="old('paid_amount', '0.00')" 
                        placeholder="0.00 (Leave 0 if Not Yet Paid)"
                    />
                    <p class="text-[11px] text-slate-400 mt-1">If paid partially or fully, enter amount paid here.</p>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Internal Audit Notes & Remarks</label>
                <textarea name="notes" rows="3" class="w-full text-xs rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3 bg-white text-slate-900 shadow-2xs" placeholder="Add any details regarding line items, bank reference numbers, or payment terms...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <x-forms.button href="{{ route('admin.supplier-payments.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-save" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Save Payment Record
                </x-forms.button>
            </div>
        </x-forms.form>
    </div>

</div>
@endsection
