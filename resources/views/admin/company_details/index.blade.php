@extends('admin.layout.app')

@section('title', 'Platform Company Details')

@section('content')
<div class="space-y-6 w-full max-w-6xl mx-auto">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-address-card text-xl"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Platform Company Profile & Banking Details</h1>
                <p class="text-xs text-slate-500">Manage legal entity info, tax registration, official contact details, and wholesale bank wire instructions</p>
            </div>
        </div>
        <div>
            <x-forms.button 
                href="{{ route('admin.company-details.edit') }}" 
                variant="primary" 
                icon="fas fa-pen-to-square"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Edit Corporate Details
            </x-forms.button>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Trading Entity</p>
                <h3 class="text-base font-bold text-slate-900 truncate max-w-[160px]">{{ $companyDetail->company_name ?? 'B2B Wholesale' }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tax Registration</p>
                <h3 class="text-base font-bold font-mono text-slate-900">{{ $companyDetail->tax_number ?? 'Not set' }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-building-columns"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Primary Bank</p>
                <h3 class="text-base font-bold text-slate-900 truncate max-w-[160px]">{{ $companyDetail->bank_name ?? 'Not set' }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fas fa-coins"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Operating Currency</p>
                <h3 class="text-base font-bold text-slate-900">{{ $companyDetail->currency ?? 'USD ($)' }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Corporate Identity & Legal (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-id-card text-indigo-600"></i>
                Corporate Identity
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <p class="text-slate-400 font-medium">Platform Trading Name</p>
                    <p class="text-slate-800 font-semibold text-sm">{{ $companyDetail->company_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Legal Registered Name</p>
                    <p class="text-slate-800 font-semibold">{{ $companyDetail->legal_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Corporate Registration No.</p>
                    <p class="text-slate-800 font-mono font-semibold">{{ $companyDetail->registration_number ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Tax / VAT ID</p>
                    <p class="text-slate-800 font-mono font-semibold">{{ $companyDetail->tax_number ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Sales & Inquiries Email</p>
                    <p class="text-indigo-600 font-semibold">
                        @if($companyDetail->email)
                            <a href="mailto:{{ $companyDetail->email }}" class="hover:underline">{{ $companyDetail->email }}</a>
                        @else
                            <span class="text-slate-400">N/A</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Support Hotline</p>
                    <p class="text-slate-800 font-semibold">{{ $companyDetail->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Official Website</p>
                    <p class="text-indigo-600 font-semibold">
                        @if($companyDetail->website)
                            <a href="{{ $companyDetail->website }}" target="_blank" class="hover:underline flex items-center gap-1">
                                <span>{{ $companyDetail->website }}</span>
                                <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        @else
                            <span class="text-slate-400">N/A</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Bank Details & Address (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Wholesale Bank Wire Details -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-building-columns text-indigo-600"></i>
                    Wholesale Bank Wire Transfer Account Details
                </h3>
                <p class="text-xs text-slate-500">These banking details are automatically generated on B2B wholesale invoices and proforma statements for wire transfers.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-400 font-medium">Bank Name</p>
                        <p class="text-sm font-bold text-slate-900">{{ $companyDetail->bank_name ?? 'Not configured' }}</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-400 font-medium">Beneficiary Account Name</p>
                        <p class="text-sm font-bold text-slate-900">{{ $companyDetail->account_name ?? 'Not configured' }}</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-400 font-medium">Account / Routing Number</p>
                        <p class="text-sm font-bold font-mono text-indigo-600">{{ $companyDetail->account_number ?? 'Not configured' }}</p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-400 font-medium">SWIFT / BIC Code</p>
                        <p class="text-sm font-bold font-mono text-indigo-600">{{ $companyDetail->swift_code ?? 'Not configured' }}</p>
                    </div>

                    <div class="sm:col-span-2 p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                        <p class="text-slate-400 font-medium">International Bank Account Number (IBAN)</p>
                        <p class="text-sm font-bold font-mono text-indigo-600">{{ $companyDetail->iban ?? 'Not configured' }}</p>
                    </div>
                </div>
            </div>

            <!-- Address & Description -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-location-dot text-indigo-600"></i>
                    Headquarters Address & Description
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-slate-400 font-medium">Street Address</p>
                        <p class="text-slate-800 font-semibold mt-0.5">{{ $companyDetail->address ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-medium">City, State & Postal Code</p>
                        <p class="text-slate-800 font-semibold mt-0.5">
                            {{ implode(', ', array_filter([$companyDetail->city, $companyDetail->state, $companyDetail->postal_code])) ?: 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-medium">Country</p>
                        <p class="text-slate-800 font-semibold mt-0.5">{{ $companyDetail->country ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-slate-400 font-medium">Platform Timezone</p>
                        <p class="text-slate-800 font-semibold mt-0.5">{{ $companyDetail->timezone ?? 'UTC' }}</p>
                    </div>
                </div>

                @if($companyDetail->description)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-slate-400 font-medium text-xs">Platform Business Overview</p>
                        <p class="text-xs text-slate-700 mt-1 leading-relaxed">{{ $companyDetail->description }}</p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
