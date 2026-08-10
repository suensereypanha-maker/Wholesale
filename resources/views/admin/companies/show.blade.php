@extends('admin.layout.app')

@section('title', 'Corporate Profile - ' . $company->name)

@section('content')
<div class="space-y-6 w-full max-w-6xl mx-auto">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.companies.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $company->name }}</h1>
                    <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-xs">
                        {{ $company->company_code }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $company->status_badge_class }}">
                        {{ $company->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                    <span><i class="fas fa-industry text-slate-400 mr-1"></i>{{ $company->industry ?? 'General Wholesale' }}</span>
                    <span>•</span>
                    <span><i class="fas fa-calendar text-slate-400 mr-1"></i>Registered {{ $company->created_at->format('M Y') }}</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button 
                href="{{ route('admin.companies.edit', $company) }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-pen-to-square"
            >
                Edit Company
            </x-forms.button>
            <x-forms.form 
                action="{{ route('admin.companies.destroy', $company) }}" 
                method="DELETE" 
                class="inline-block !space-y-0"
                onsubmit="return confirm('Are you sure you want to delete company {{ $company->name }}?');"
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

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Credit Limit -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fas fa-credit-card"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Approved Credit Limit</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($company->credit_limit, 2) }}</h3>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Company Workforce</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($company->total_employees ?? 0) }} Staff</h3>
            </div>
        </div>

        <!-- Tax Registration -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tax ID / VAT Reg</p>
                <h3 class="text-lg font-bold font-mono text-slate-900">{{ $company->tax_id ?? 'Not Registered' }}</h3>
            </div>
        </div>
    </div>

    <!-- Details Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Identity & Contact Info (1 col) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-building text-indigo-600"></i>
                Corporate Details
            </h3>

            <div class="space-y-3 text-xs">
                <div>
                    <p class="text-slate-400 font-medium">Official Company Name</p>
                    <p class="text-slate-800 font-semibold text-sm">{{ $company->name }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Industry Sector</p>
                    <p class="text-slate-800 font-semibold">{{ $company->industry ?? 'General Wholesale' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Corporate Email</p>
                    <p class="text-indigo-600 font-semibold">
                        @if($company->email)
                            <a href="mailto:{{ $company->email }}" class="hover:underline">{{ $company->email }}</a>
                        @else
                            <span class="text-slate-400">N/A</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Telephone</p>
                    <p class="text-slate-800 font-semibold">{{ $company->phone ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-slate-400 font-medium">Website</p>
                    <p class="text-indigo-600 font-semibold">
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="hover:underline flex items-center gap-1">
                                <span>{{ $company->website }}</span>
                                <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
                            </a>
                        @else
                            <span class="text-slate-400">N/A</span>
                        @endif
                    </p>
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <p class="text-slate-400 font-medium">Headquarters Address</p>
                    <p class="text-slate-700 mt-1 leading-relaxed">
                        {{ $company->address ?? 'No address registered' }}
                        @if($company->city || $company->country)
                            <br><span class="font-semibold">{{ implode(', ', array_filter([$company->city, $company->country])) }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Corporate Notes (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <h3 class="text-base font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fas fa-note-sticky text-indigo-600"></i>
                    Relationship Notes & History
                </h3>

                @if($company->notes)
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 text-slate-700 text-xs leading-relaxed">
                        <i class="fas fa-quote-left text-slate-400 mr-2"></i>
                        {{ $company->notes }}
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No notes recorded for this company.</p>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
