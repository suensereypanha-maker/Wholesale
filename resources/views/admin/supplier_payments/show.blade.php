@extends('admin.layout.app')

@section('title', 'Payment Advice - ' . $supplierPayment->payment_code)

@section('content')
<div class="space-y-6 w-full max-w-4xl mx-auto">

    <!-- Action Bar & Back Link -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs print:hidden">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.supplier-payments.index') }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Supplier Payment Advice</h1>
                <p class="text-xs text-slate-500">Ref Code: <span class="font-mono font-bold text-indigo-600">{{ $supplierPayment->payment_code }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-2 transition-colors">
                <i class="fas fa-print"></i>
                Print Advice
            </button>
            <x-forms.button href="{{ route('admin.supplier-payments.edit', $supplierPayment) }}" variant="primary" icon="fas fa-pen-to-square" class="!bg-indigo-600 hover:!bg-indigo-700">
                Edit Record
            </x-forms.button>
        </div>
    </div>

    <!-- Payment Advice / Invoice Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-8">
        
        <!-- Document Header -->
        <div class="flex justify-between items-start border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-boxes-packing"></i>
                    </div>
                    <span class="text-lg font-black text-slate-900 tracking-tight">B2B Wholesale Hub</span>
                </div>
                <p class="text-xs text-slate-500">Procurement & Supplier Accounts Payable</p>
            </div>

            <div class="text-right space-y-1">
                <span class="px-3 py-1 rounded-full text-xs font-bold border capitalize {{ $supplierPayment->status_badge }}">
                    {{ $supplierPayment->status_label }}
                </span>
                <p class="text-xs font-mono font-bold text-slate-700 pt-2">{{ $supplierPayment->payment_code }}</p>
                <p class="text-[11px] text-slate-400">Created: {{ $supplierPayment->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>

        <!-- Supplier & Invoice Meta Details -->
        <div class="grid grid-cols-2 gap-6 bg-slate-50/70 p-5 rounded-xl border border-slate-200/60">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Supplier Details</p>
                <h3 class="text-sm font-bold text-slate-900">{{ $supplierPayment->supplier->name ?? 'N/A' }}</h3>
                @if($supplierPayment->supplier->company_name)
                    <p class="text-xs text-slate-600 font-semibold">{{ $supplierPayment->supplier->company_name }}</p>
                @endif
                <p class="text-xs text-slate-500 mt-1">{{ $supplierPayment->supplier->email ?? 'No Email' }} • {{ $supplierPayment->supplier->phone ?? 'No Phone' }}</p>
                <p class="text-xs text-slate-500">Payment Terms: <span class="font-bold text-slate-700">{{ $supplierPayment->supplier->payment_terms ?? 'Net 30' }}</span></p>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Invoice & Payment Info</p>
                <p class="text-xs text-slate-700">Supplier Invoice #: <span class="font-bold font-mono">{{ $supplierPayment->invoice_number ?? 'N/A' }}</span></p>
                <p class="text-xs text-slate-700 mt-0.5">Purchase Date: <span class="font-semibold">{{ $supplierPayment->purchase_date->format('M d, Y') }}</span></p>
                <p class="text-xs text-slate-700 mt-0.5">Payment Due Date: <span class="font-semibold text-rose-600">{{ $supplierPayment->due_date->format('M d, Y') }}</span></p>
                <p class="text-xs text-slate-700 mt-0.5">Payment Method: <span class="font-semibold">{{ $supplierPayment->payment_method ?? 'Not Specified' }}</span></p>
            </div>
        </div>

        <!-- Financial Summary Table -->
        <div>
            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Financial Settlement Breakdown</h4>
            <div class="border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100/80 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase">
                        <tr>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-right">Amount ($)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        <tr>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">Total Bill / Purchase Obligation</td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-900">${{ number_format($supplierPayment->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 px-4 font-semibold text-emerald-700 flex items-center gap-2">
                                <i class="fas fa-circle-check text-xs"></i>
                                Total Amount Paid (Total Pay)
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-emerald-600">-${{ number_format($supplierPayment->paid_amount, 2) }}</td>
                        </tr>
                        <tr class="bg-slate-50">
                            <td class="py-4 px-4 font-bold text-sm text-slate-900">Remaining Balance Due (Not Yet Paid)</td>
                            <td class="py-4 px-4 text-right font-black text-base {{ $supplierPayment->due_amount > 0 ? 'text-rose-600' : 'text-slate-500' }}">
                                ${{ number_format($supplierPayment->due_amount, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audit Log / Notes -->
        @if($supplierPayment->notes)
            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Audit & Transaction History Notes</h4>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 whitespace-pre-line font-mono">
                    {{ $supplierPayment->notes }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
