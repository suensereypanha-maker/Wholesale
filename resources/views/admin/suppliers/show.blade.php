@extends('admin.layout.app')

@section('title', 'Supplier Profile - ' . $supplier->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.suppliers.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $supplier->name }}</h1>
                    <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono font-bold text-xs rounded-lg border border-indigo-100">
                        {{ $supplier->code }}
                    </span>
                    @php
                        $badgeClasses = [
                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
                        ];
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $badgeClasses[$supplier->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $supplier->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">{{ $supplier->company_name ?? 'Individual Supplier' }} • Registered {{ $supplier->created_at->format('M d, Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button href="{{ route('admin.suppliers.edit', $supplier) }}" variant="secondary" icon="fas fa-pen-to-square">
                Edit Supplier
            </x-forms.button>

            <x-forms.form 
                action="{{ route('admin.suppliers.destroy', $supplier) }}" 
                method="DELETE" 
                class="inline-block !space-y-0"
                onsubmit="return confirm('Are you sure you want to delete supplier {{ $supplier->name }}?');"
            >
                <x-forms.button 
                    type="submit" 
                    variant="danger" 
                    icon="fas fa-trash-can"
                >
                    Delete
                </x-forms.button>
            </x-forms.form>
        </div>
    </div>

    <!-- Main Grid Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Primary Details -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Commercial Overview Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-building"></i> Commercial Overview & Terms
                </h2>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Payment Terms</span>
                        <span class="text-base font-bold text-slate-900 mt-0.5 block">{{ $supplier->payment_terms }}</span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Vendor Rating</span>
                        <div class="flex items-center gap-1 text-amber-400 text-sm mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $supplier->rating ? '' : 'text-slate-200' }}"></i>
                            @endfor
                            <span class="text-xs font-bold text-slate-800 ml-1">({{ $supplier->rating }}/5)</span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Category</span>
                        <span class="text-sm font-bold text-slate-900 mt-1 block">{{ $supplier->category ?? 'General' }}</span>
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Tax ID / VAT Registration</span>
                        <span class="font-mono font-bold text-slate-800">{{ $supplier->tax_id ?? 'Not Provided' }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Enterprise Name</span>
                        <span class="font-semibold text-slate-800">{{ $supplier->company_name ?? 'N/A' }}</span>
                    </div>

                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-slate-500 font-medium">Contact Person</span>
                        <span class="font-semibold text-slate-800">{{ $supplier->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes & Additional Comments Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i> Operational Notes & Audit Log
                </h2>
                <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 min-h-[80px]">
                    {{ $supplier->notes ?? 'No notes recorded for this supplier.' }}
                </p>
            </div>

        </div>

        <!-- Column 3: Contact & Address Sidebar Card -->
        <div class="space-y-6">

            <!-- Contact Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-address-card"></i> Contact Information
                </h2>

                <div class="space-y-3 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 block">Email Address</span>
                            @if($supplier->email)
                                <a href="mailto:{{ $supplier->email }}" class="font-semibold text-indigo-600 hover:underline">
                                    {{ $supplier->email }}
                                </a>
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-xs"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 block">Phone Number</span>
                            @if($supplier->phone)
                                <a href="tel:{{ $supplier->phone }}" class="font-semibold text-slate-800 hover:text-indigo-600">
                                    {{ $supplier->phone }}
                                </a>
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-globe text-xs"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-medium text-slate-400 block">Website URL</span>
                            @if($supplier->website)
                                <a href="{{ $supplier->website }}" target="_blank" class="font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                                    {{ parse_url($supplier->website, PHP_URL_HOST) ?? $supplier->website }}
                                    <i class="fas fa-external-link text-[10px]"></i>
                                </a>
                            @else
                                <span class="text-slate-400">N/A</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-location-dot"></i> Facility Address
                </h2>

                <div class="text-xs text-slate-600 space-y-1">
                    <p class="font-semibold text-slate-900">{{ $supplier->company_name ?? $supplier->name }}</p>
                    <p>{{ $supplier->address ?? 'No street address specified' }}</p>
                    <p>{{ $supplier->city }}</p>
                    <p class="font-medium text-slate-800">{{ $supplier->country }}</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
