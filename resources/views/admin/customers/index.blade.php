@extends('admin.layout.app')

@section('title', 'Wholesale Customers Directory')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-users-gear text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Wholesale & Bulk Customers</h1>
                    <p class="text-xs text-slate-500">Manage high-volume B2B clients, bulk buyers, tier discounts, and credit terms</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.customers.create') }}" 
                variant="primary" 
                icon="fas fa-user-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add Wholesale Customer
            </x-forms.button>
        </div>
    </div>

    <!-- Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total B2B Buyers</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalCustomers) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-sack-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Wholesale Revenue</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($totalWholesaleSpent, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-crown text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">VIP / Gold Accounts</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($topTierCount) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Accounts</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($activeCustomers) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search code, customer, company, email..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="tier" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Wholesale Tiers</option>
                        @foreach($tiers as $t)
                            <option value="{{ $t }}" {{ request('tier') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'status', 'tier']))
                    <x-forms.button href="{{ route('admin.customers.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Code</th>
                        <th class="py-3.5 px-5">Customer & Company</th>
                        <th class="py-3.5 px-5">Wholesale Tier</th>
                        <th class="py-3.5 px-5">Discount Rate</th>
                        <th class="py-3.5 px-5">Credit Limit</th>
                        <th class="py-3.5 px-5">Total Wholesale Spent</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Code -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-[11px]">
                                    {{ $customer->customer_code }}
                                </span>
                            </td>

                            <!-- Customer & Company -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 group-hover:text-indigo-600">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="hover:underline">
                                        {{ $customer->name }}
                                    </a>
                                </div>
                                @if($customer->company_name)
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-building text-slate-400"></i>
                                        <span>{{ $customer->company_name }}</span>
                                    </div>
                                @endif
                                @if($customer->email)
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $customer->email }}
                                    </div>
                                @endif
                            </td>

                            <!-- Tier -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border inline-flex items-center gap-1.5 {{ $customer->tier_badge_class }}">
                                    @if(str_contains($customer->tier, 'VIP'))
                                        <i class="fas fa-crown text-[10px]"></i>
                                    @else
                                        <i class="fas fa-layer-group text-[10px]"></i>
                                    @endif
                                    {{ $customer->tier }}
                                </span>
                            </td>

                            <!-- Discount Rate -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold border border-emerald-100">
                                    {{ number_format($customer->wholesale_discount, 1) }}% OFF
                                </span>
                            </td>

                            <!-- Credit Limit -->
                            <td class="py-4 px-5 font-semibold text-slate-700">
                                ${{ number_format($customer->credit_limit, 2) }}
                                <div class="text-[10px] text-slate-400 font-normal">Terms: {{ $customer->payment_terms }}</div>
                            </td>

                            <!-- Total Wholesale Spent -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900">
                                    ${{ number_format($customer->total_spent, 2) }}
                                </div>
                                <div class="text-[11px] text-slate-500">
                                    {{ number_format($customer->total_orders) }} orders placed
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize {{ $customer->status_badge_class }}">
                                    {{ $customer->status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-forms.button 
                                        href="{{ route('admin.customers.show', $customer) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Customer Profile" 
                                    />
                                    <x-forms.button 
                                        href="{{ route('admin.customers.edit', $customer) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        title="Edit Customer" 
                                    />
                                    <x-forms.form 
                                        action="{{ route('admin.customers.destroy', $customer) }}" 
                                        method="DELETE" 
                                        class="inline-block !space-y-0"
                                        onsubmit="return confirm('Are you sure you want to delete customer {{ $customer->name }}?');"
                                    >
                                        <x-forms.button 
                                            type="submit" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon="fas fa-trash-can" 
                                            class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                            title="Delete Customer"
                                        />
                                    </x-forms.form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-users-slash text-3xl text-slate-300"></i>
                                <p class="text-sm font-medium text-slate-600">No wholesale customers found.</p>
                                <p class="text-xs text-slate-400">Try adjusting your filters or add a new wholesale buyer account.</p>
                                <div class="pt-2">
                                    <x-forms.button href="{{ route('admin.customers.create') }}" variant="primary" icon="fas fa-user-plus">
                                        Add Wholesale Customer
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
