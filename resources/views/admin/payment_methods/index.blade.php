@extends('admin.layout.app')

@section('title', 'Payment Methods Directory')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-credit-card text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Payment Methods</h1>
                    <p class="text-xs text-slate-500">Manage payment channels, bank accounts, QR gateways, cash, and credit lines</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.payment-methods.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add Payment Method
            </x-forms.button>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Methods</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalMethods) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Active Channels</p>
                <h3 class="text-2xl font-bold text-emerald-600">{{ number_format($activeMethods) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider">Bank Accounts</p>
                <h3 class="text-2xl font-bold text-blue-600">{{ number_format($bankMethods) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-building-columns"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-purple-700 uppercase tracking-wider">Digital & Cash</p>
                <h3 class="text-2xl font-bold text-purple-600">{{ number_format($digitalMethods) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-qrcode"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.payment-methods.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search code, method name, account #..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="type" class="w-full text-xs rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 bg-white font-medium">
                        <option value="">All Channel Types</option>
                        @foreach($types as $typeKey => $typeVal)
                            <option value="{{ $typeKey }}" {{ request('type') == $typeKey ? 'selected' : '' }}>{{ $typeVal }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-36">
                    <select name="status" class="w-full text-xs rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 bg-white font-medium">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'type', 'status']))
                    <x-forms.button href="{{ route('admin.payment-methods.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Code</th>
                        <th class="py-3.5 px-5">Method Name</th>
                        <th class="py-3.5 px-5">Channel Type</th>
                        <th class="py-3.5 px-5">Account / Bank Details</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($paymentMethods as $method)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-[11px]">
                                    {{ $method->code }}
                                </span>
                            </td>

                            <td class="py-4 px-5 font-bold text-slate-900">
                                <a href="{{ route('admin.payment-methods.show', $method) }}" class="hover:text-indigo-600 hover:underline">
                                    {{ $method->name }}
                                </a>
                                @if($method->notes)
                                    <p class="text-[11px] text-slate-400 font-normal truncate max-w-xs">{{ $method->notes }}</p>
                                @endif
                            </td>

                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border capitalize {{ $method->type_badge }}">
                                    {{ $types[$method->type] ?? ucfirst($method->type) }}
                                </span>
                            </td>

                            <td class="py-4 px-5">
                                @if($method->account_number || $method->account_name)
                                    <div class="font-semibold text-slate-800">{{ $method->account_name ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $method->account_number ?? '' }}</div>
                                @else
                                    <span class="text-slate-400 font-italic">Standard Payment Channel</span>
                                @endif
                            </td>

                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize {{ $method->status_badge }}">
                                    {{ $method->status }}
                                </span>
                            </td>

                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-forms.button 
                                        href="{{ route('admin.payment-methods.show', $method) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Method" 
                                    />
                                    <x-forms.button 
                                        href="{{ route('admin.payment-methods.edit', $method) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        title="Edit Method" 
                                    />
                                    <x-forms.form 
                                        action="{{ route('admin.payment-methods.destroy', $method) }}" 
                                        method="DELETE" 
                                        class="inline-block !space-y-0"
                                        onsubmit="return confirm('Are you sure you want to delete payment method {{ $method->name }}?');"
                                    >
                                        <x-forms.button 
                                            type="submit" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon="fas fa-trash-can" 
                                            class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                            title="Delete Method"
                                        />
                                    </x-forms.form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-credit-card text-4xl text-slate-300"></i>
                                <p class="text-sm font-semibold text-slate-700">No payment methods configured.</p>
                                <div>
                                    <x-forms.button href="{{ route('admin.payment-methods.create') }}" variant="primary" icon="fas fa-plus">
                                        Add Payment Method
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paymentMethods->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $paymentMethods->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
