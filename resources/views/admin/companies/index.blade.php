@extends('admin.layout.app')

@section('title', 'Corporate Companies Directory')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-building text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">B2B Companies & Corporate Accounts</h1>
                    <p class="text-xs text-slate-500">Manage corporate partners, industry sectors, credit limits, and business contacts</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.companies.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add New Company
            </x-forms.button>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Companies</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalCompanies) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Corporate Accounts</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($activeCompanies) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-credit-card text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Approved Credit</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($totalCreditLimit, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-industry text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Industries Represented</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($industriesCount) }}</h3>
            </div>
        </div>
    </div>

    <!-- Toolbar Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.companies.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search code, name, tax ID, email..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-56">
                    <select name="industry" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Industries</option>
                        @foreach($industries as $ind)
                            <option value="{{ $ind }}" {{ request('industry') == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'status', 'industry']))
                    <x-forms.button href="{{ route('admin.companies.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
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
                        <th class="py-3.5 px-5">Company Name & Tax ID</th>
                        <th class="py-3.5 px-5">Industry</th>
                        <th class="py-3.5 px-5">Contact Details</th>
                        <th class="py-3.5 px-5">Location</th>
                        <th class="py-3.5 px-5">Credit Limit</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($companies as $company)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Code -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-[11px]">
                                    {{ $company->company_code }}
                                </span>
                            </td>

                            <!-- Name & Tax ID -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 group-hover:text-indigo-600">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="hover:underline">
                                        {{ $company->name }}
                                    </a>
                                </div>
                                @if($company->tax_id)
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5 font-mono">
                                        <i class="fas fa-file-invoice text-slate-400"></i>
                                        <span>TAX: {{ $company->tax_id }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Industry -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    <i class="fas fa-briefcase text-[10px] text-slate-400"></i>
                                    {{ $company->industry ?? 'General Wholesale' }}
                                </span>
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-5">
                                <div class="space-y-0.5">
                                    @if($company->email)
                                        <div class="text-slate-700 flex items-center gap-1.5">
                                            <i class="fas fa-envelope text-slate-400 w-3.5 text-center"></i>
                                            <span>{{ $company->email }}</span>
                                        </div>
                                    @endif
                                    @if($company->phone)
                                        <div class="text-slate-500 text-[11px] flex items-center gap-1.5">
                                            <i class="fas fa-phone text-slate-400 w-3.5 text-center"></i>
                                            <span>{{ $company->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Location -->
                            <td class="py-4 px-5 text-slate-700">
                                {{ implode(', ', array_filter([$company->city, $company->country])) ?: 'N/A' }}
                            </td>

                            <!-- Credit Limit -->
                            <td class="py-4 px-5 font-bold text-slate-900">
                                ${{ number_format($company->credit_limit, 2) }}
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize {{ $company->status_badge_class }}">
                                    {{ $company->status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-forms.button 
                                        href="{{ route('admin.companies.show', $company) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Company Profile" 
                                    />
                                    <x-forms.button 
                                        href="{{ route('admin.companies.edit', $company) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        title="Edit Company" 
                                    />
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
                                            class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                            title="Delete Company"
                                        />
                                    </x-forms.form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-building text-3xl text-slate-300"></i>
                                <p class="text-sm font-medium text-slate-600">No corporate companies found.</p>
                                <p class="text-xs text-slate-400">Try adjusting your search criteria or register a new company.</p>
                                <div class="pt-2">
                                    <x-forms.button href="{{ route('admin.companies.create') }}" variant="primary" icon="fas fa-plus">
                                        Add Company
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
