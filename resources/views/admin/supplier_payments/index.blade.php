@extends('admin.layout.app')

@section('title', 'Supplier Payments & Obligations')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Supplier Payments & Obligations</h1>
                    <p class="text-xs text-slate-500">Track purchase bills, supplier total pay status, and outstanding balances ("Total Paid" vs "Not Yet Paid")</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.supplier-payments.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Create Payment Record
            </x-forms.button>
        </div>
    </div>

    <!-- KPI Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Payables -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Purchase Bills</p>
                <h3 class="text-2xl font-bold text-slate-900">${{ number_format($totalPayable, 2) }}</h3>
                <p class="text-[11px] text-slate-400">All supplier invoices</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-receipt"></i>
            </div>
        </div>

        <!-- Total Paid ("Total Pay") -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Paid (Total Pay)</p>
                </div>
                <h3 class="text-2xl font-bold text-emerald-600">${{ number_format($totalPaid, 2) }}</h3>
                <p class="text-[11px] text-emerald-600 font-semibold">{{ $paidCount }} bills fully settled</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>

        <!-- Not Yet Paid ("No Yet") -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <p class="text-xs font-bold text-rose-700 uppercase tracking-wider">Not Yet Paid (No Yet)</p>
                </div>
                <h3 class="text-2xl font-bold text-rose-600">${{ number_format($totalUnpaid, 2) }}</h3>
                <p class="text-[11px] text-rose-600 font-semibold">{{ $unpaidCount + $partialCount }} pending balances</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>

        <!-- Overdue Count -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Overdue Payments</p>
                <h3 class="text-2xl font-bold text-amber-600">{{ $overdueCount }}</h3>
                <p class="text-[11px] text-amber-600 font-semibold">Bills past due date</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
        </div>
    </div>

    <!-- Quick Status Filter Pills -->
    <div class="flex flex-wrap items-center gap-2 bg-slate-100/80 p-1.5 rounded-xl w-fit border border-slate-200/60">
        <a href="{{ route('admin.supplier-payments.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
           class="px-4 py-2 rounded-lg text-xs font-bold transition-all {{ request('status', 'all') === 'all' ? 'bg-white text-indigo-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
            All Bills ({{ $payments->total() }})
        </a>
        <a href="{{ route('admin.supplier-payments.index', array_merge(request()->except('status', 'page'), ['status' => 'paid'])) }}" 
           class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700' }}">
            <i class="fas fa-check-circle text-xs"></i>
            Total Paid ({{ $paidCount }})
        </a>
        <a href="{{ route('admin.supplier-payments.index', array_merge(request()->except('status', 'page'), ['status' => 'unpaid'])) }}" 
           class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'unpaid' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:text-rose-700' }}">
            <i class="fas fa-times-circle text-xs"></i>
            Not Yet Paid ({{ $unpaidCount }})
        </a>
        <a href="{{ route('admin.supplier-payments.index', array_merge(request()->except('status', 'page'), ['status' => 'partial'])) }}" 
           class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 {{ request('status') === 'partial' ? 'bg-amber-600 text-white shadow-xs' : 'text-slate-600 hover:text-amber-700' }}">
            <i class="fas fa-adjust text-xs"></i>
            Partially Paid ({{ $partialCount }})
        </a>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.supplier-payments.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search payment code, invoice #, supplier..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-60">
                    <select name="supplier_id" class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3 bg-white text-slate-900 shadow-2xs">
                        <option value="">All Suppliers</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }} ({{ $sup->company_name ?? $sup->code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'supplier_id']) || (request('status') && request('status') !== 'all'))
                    <x-forms.button href="{{ route('admin.supplier-payments.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset Filters
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Payments Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Ref Code</th>
                        <th class="py-3.5 px-5">Supplier Name</th>
                        <th class="py-3.5 px-5">Invoice & Due Date</th>
                        <th class="py-3.5 px-5">Total Bill</th>
                        <th class="py-3.5 px-5">Paid Amount</th>
                        <th class="py-3.5 px-5">Balance Due</th>
                        <th class="py-3.5 px-5">Payment Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($payments as $payment)
                        @php
                            $isOverdue = $payment->payment_status !== 'paid' && $payment->due_date->isPast();
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Ref Code -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-[11px]">
                                    {{ $payment->payment_code }}
                                </span>
                            </td>

                            <!-- Supplier -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900">
                                    <a href="{{ route('admin.suppliers.show', $payment->supplier) }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $payment->supplier->name ?? 'Unknown Supplier' }}
                                    </a>
                                </div>
                                @if($payment->supplier->company_name)
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-building text-slate-400"></i>
                                        <span>{{ $payment->supplier->company_name }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Invoice & Due Date -->
                            <td class="py-4 px-5">
                                <div class="font-semibold text-slate-800">
                                    {{ $payment->invoice_number ?? 'N/A' }}
                                </div>
                                <div class="text-[11px] flex items-center gap-1.5 mt-0.5 {{ $isOverdue ? 'text-rose-600 font-bold' : 'text-slate-500' }}">
                                    <i class="fas fa-calendar-day text-[10px] {{ $isOverdue ? 'text-rose-500' : 'text-slate-400' }}"></i>
                                    <span>Due: {{ $payment->due_date->format('M d, Y') }}</span>
                                    @if($isOverdue)
                                        <span class="px-1.5 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-100 text-rose-700 uppercase">Overdue</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Total Bill -->
                            <td class="py-4 px-5 font-bold text-slate-900">
                                ${{ number_format($payment->total_amount, 2) }}
                            </td>

                            <!-- Paid Amount -->
                            <td class="py-4 px-5 font-bold text-emerald-600">
                                ${{ number_format($payment->paid_amount, 2) }}
                            </td>

                            <!-- Balance Due -->
                            <td class="py-4 px-5 font-bold {{ $payment->due_amount > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                ${{ number_format($payment->due_amount, 2) }}
                            </td>

                            <!-- Payment Status -->
                            <td class="py-4 px-5">
                                @if($payment->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <i class="fas fa-circle-check text-xs"></i>
                                        Total Paid
                                    </span>
                                @elseif($payment->payment_status === 'partial')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <i class="fas fa-adjust text-xs"></i>
                                        Partially Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        <i class="fas fa-clock text-xs"></i>
                                        Not Yet Paid
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($payment->payment_status !== 'paid')
                                        <button 
                                            type="button"
                                            onclick="openPayModal('{{ $payment->id }}', '{{ $payment->payment_code }}', '{{ addslashes($payment->supplier->name) }}', '{{ number_format($payment->due_amount, 2, '.', '') }}')"
                                            class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs flex items-center gap-1 transition-all shadow-xs"
                                            title="Pay Supplier Now"
                                        >
                                            <i class="fas fa-hand-holding-dollar text-xs"></i>
                                            Pay Now
                                        </button>
                                    @endif

                                    <x-forms.button 
                                        href="{{ route('admin.supplier-payments.show', $payment) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Voucher & Log" 
                                    />
                                    <x-forms.button 
                                        href="{{ route('admin.supplier-payments.edit', $payment) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        title="Edit Payment Record" 
                                    />
                                    <x-forms.form 
                                        action="{{ route('admin.supplier-payments.destroy', $payment) }}" 
                                        method="DELETE" 
                                        class="inline-block !space-y-0"
                                        onsubmit="return confirm('Are you sure you want to delete payment record {{ $payment->payment_code }}?');"
                                    >
                                        <x-forms.button 
                                            type="submit" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon="fas fa-trash-can" 
                                            class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                            title="Delete Record"
                                        />
                                    </x-forms.form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-file-invoice-dollar text-4xl text-slate-300"></i>
                                <p class="text-sm font-semibold text-slate-700">No supplier payment records found.</p>
                                <p class="text-xs text-slate-400">Click below to create a new purchase bill or record payment.</p>
                                <div class="pt-2">
                                    <x-forms.button href="{{ route('admin.supplier-payments.create') }}" variant="primary" icon="fas fa-plus">
                                        Create Payment Record
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Quick "Record Payment" Modal -->
<div id="payModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 transform transition-all space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                    <i class="fas fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Record Payment to Supplier</h3>
                    <p class="text-xs text-slate-500" id="modalPaymentRef">Ref Code: </p>
                </div>
            </div>
            <button onclick="closePayModal()" type="button" class="text-slate-400 hover:text-slate-600 p-1">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="recordPaymentForm" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Supplier</label>
                <input type="text" id="modalSupplierName" readonly class="w-full text-xs font-semibold rounded-xl bg-slate-100 border-2 border-slate-300 text-slate-700 py-2.5 px-3">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Remaining Due Balance</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">$</span>
                    <input type="text" id="modalDueBalance" readonly class="w-full pl-7 text-sm font-extrabold rounded-xl bg-rose-50 text-rose-700 border-2 border-rose-200 py-2.5 px-3">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Amount to Pay ($) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-bold text-xs">$</span>
                    <input type="number" step="0.01" min="0.01" name="amount_to_pay" id="modalAmountInput" required class="w-full pl-7 text-sm font-bold rounded-xl border-2 border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 py-2.5 px-3">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Payment Method <span class="text-rose-500">*</span></label>
                    <select name="payment_method" required class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 py-2.5 px-3 bg-white text-slate-900">
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method }}">{{ $method }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Payment Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 py-2.5 px-3 bg-white text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Payment Notes / Transaction ID</label>
                <textarea name="payment_notes" rows="2" placeholder="e.g. Bank Ref #TRX-998231" class="w-full text-xs rounded-xl border-2 border-slate-300 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 py-2.5 px-3 bg-white text-slate-900"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closePayModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md shadow-emerald-600/20 flex items-center gap-2">
                    <i class="fas fa-check"></i>
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPayModal(id, code, supplier, due) {
        const form = document.getElementById('recordPaymentForm');
        form.action = `/admin/supplier-payments/${id}/pay`;
        document.getElementById('modalPaymentRef').innerText = 'Ref Code: ' + code;
        document.getElementById('modalSupplierName').value = supplier;
        document.getElementById('modalDueBalance').value = due;
        document.getElementById('modalAmountInput').value = due;
        document.getElementById('payModal').classList.remove('hidden');
    }

    function closePayModal() {
        document.getElementById('payModal').classList.add('hidden');
    }
</script>
@endsection
