@extends('admin.layout.app')

@section('title', 'Wholesale Customer Profile - ' . $customer->name)

@section('content')
<div class="space-y-6 w-full max-w-6xl mx-auto">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.customers.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $customer->name }}</h1>
                    <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-xs">
                        {{ $customer->customer_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $customer->status_badge_class }}">
                        {{ $customer->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                    @if($customer->company_name)
                        <span><i class="fas fa-building text-slate-400 mr-1"></i>{{ $customer->company_name }}</span>
                        <span>•</span>
                    @endif
                    <span><i class="fas fa-calendar text-slate-400 mr-1"></i>Member since {{ $customer->created_at->format('M Y') }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button 
                href="{{ route('admin.customers.edit', $customer) }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-pen-to-square"
            >
                Edit Account
            </x-forms.button>
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
                    class="text-rose-500 hover:text-rose-700 hover:bg-rose-50 border border-slate-200"
                >
                    Delete
                </x-forms.button>
            </x-forms.form>
        </div>
    </div>

    <!-- Wholesale Summary KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Wholesale Tier -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-crown"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Wholesale Tier</p>
                <h3 class="text-lg font-bold text-slate-900">{{ $customer->tier }}</h3>
            </div>
        </div>

        <!-- Wholesale Discount -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-percent"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bulk Discount Rate</p>
                <h3 class="text-xl font-bold text-emerald-600">{{ number_format($customer->wholesale_discount, 1) }}% OFF</h3>
            </div>
        </div>

        <!-- Total Wholesale Volume -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fas fa-sack-dollar"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Wholesale Spent</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($customer->total_spent, 2) }}</h3>
            </div>
        </div>

        <!-- Credit Limit -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Approved Credit Limit</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($customer->credit_limit, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Contact & Business Identity Card (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-address-card text-indigo-600"></i>
                Client Contact Details
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <p class="text-slate-400 font-medium">Company Name</p>
                    <p class="text-slate-800 font-semibold text-sm">{{ $customer->company_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Primary Contact</p>
                    <p class="text-slate-800 font-semibold">{{ $customer->name }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Email Address</p>
                    <p class="text-indigo-600 font-semibold">
                        @if($customer->email)
                            <a href="mailto:{{ $customer->email }}" class="hover:underline">{{ $customer->email }}</a>
                        @else
                            <span class="text-slate-400">Not provided</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Phone Number</p>
                    <p class="text-slate-800 font-semibold">{{ $customer->phone ?? 'Not provided' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Tax / VAT ID</p>
                    <p class="text-slate-800 font-mono font-semibold">{{ $customer->tax_id ?? 'N/A' }}</p>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <p class="text-slate-400 font-medium">Location / Shipping Address</p>
                    <p class="text-slate-700 mt-1 leading-relaxed">
                        {{ $customer->address ?? 'No address recorded' }}
                        @if($customer->city || $customer->country)
                            <br><span class="font-semibold">{{ implode(', ', array_filter([$customer->city, $customer->country])) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Wholesale Terms & Performance (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Wholesale Agreement Details -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-handshake text-indigo-600"></i>
                    B2B Agreement & Credit Terms
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-500">Payment Terms</p>
                        <p class="text-sm font-bold text-slate-900">{{ $customer->payment_terms }}</p>
                        <p class="text-[11px] text-slate-400">Standard invoice due period for bulk orders</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-500">Wholesale Discount Tier</p>
                        <p class="text-sm font-bold text-indigo-600">{{ $customer->tier }}</p>
                        <p class="text-[11px] text-slate-400">Eligible for catalog wholesale pricing</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-500">Lifetime Wholesale Orders</p>
                        <p class="text-sm font-bold text-slate-900">{{ number_format($customer->total_orders) }} Orders</p>
                        <p class="text-[11px] text-slate-400">Total order volume processed</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-500">Credit Limit Status</p>
                        <p class="text-sm font-bold text-emerald-600">${{ number_format($customer->credit_limit, 2) }} Approved</p>
                        <p class="text-[11px] text-slate-400">Maximum allowed credit line</p>
                    </div>
                </div>
            </div>

            <!-- Notes & Remarks Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-note-sticky text-indigo-600"></i>
                    Special Instructions & Internal Remarks
                </h3>

                @if($customer->notes)
                    <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 text-slate-700 text-xs leading-relaxed">
                        <i class="fas fa-quote-left text-amber-400 mr-2"></i>
                        {{ $customer->notes }}
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No notes or special instructions recorded for this client.</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
