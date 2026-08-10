@extends('admin.layout.app')

@section('title', 'Edit B2B Company - ' . $company->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.companies.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Corporate Partner: {{ $company->name }}</h1>
                <p class="text-xs text-slate-500">Update company credentials, credit limits, and contact info for {{ $company->company_code }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button 
                href="{{ route('admin.companies.show', $company) }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-eye"
            >
                View Profile
            </x-forms.button>
        </div>
    </div>

    <!-- Form Body Card -->
    <x-forms.form action="{{ route('admin.companies.update', $company) }}" method="PUT" class="space-y-6">
        
        <!-- Corporate Identity Section -->
        <x-forms.card title="Company Identity" subtitle="Legal business credentials and industry sector">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="company_code" 
                    label="Company Code" 
                    :value="old('company_code', $company->company_code)" 
                    required 
                    icon="fas fa-barcode"
                />

                <x-forms.input 
                    name="name" 
                    label="Company / Corporate Name" 
                    :value="old('name', $company->name)" 
                    required 
                    icon="fas fa-building"
                />

                <x-forms.select 
                    name="industry" 
                    label="Industry Sector" 
                    :options="array_combine($industries, $industries)" 
                    :selected="old('industry', $company->industry)" 
                    required
                    icon="fas fa-industry"
                />

                <x-forms.input 
                    name="tax_id" 
                    label="Tax Identification / VAT Registration" 
                    :value="old('tax_id', $company->tax_id)" 
                    icon="fas fa-file-invoice"
                />
            </div>
        </x-forms.card>

        <!-- Communication & Corporate Financials -->
        <x-forms.card title="Contact Details & Credit Terms" subtitle="Corporate contact details and approved wholesale credit limit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="email" 
                    type="email" 
                    label="Corporate Email Address" 
                    :value="old('email', $company->email)" 
                    icon="fas fa-envelope"
                />

                <x-forms.input 
                    name="phone" 
                    label="Corporate Telephone" 
                    :value="old('phone', $company->phone)" 
                    icon="fas fa-phone"
                />

                <x-forms.input 
                    name="website" 
                    type="url" 
                    label="Official Website URL" 
                    :value="old('website', $company->website)" 
                    icon="fas fa-globe"
                />

                <x-forms.input 
                    name="total_employees" 
                    type="number" 
                    label="Total Company Employees" 
                    :value="old('total_employees', $company->total_employees)" 
                    icon="fas fa-users"
                />

                <x-forms.input 
                    name="credit_limit" 
                    type="number" 
                    step="0.01" 
                    label="Approved Credit Limit ($)" 
                    :value="old('credit_limit', $company->credit_limit)" 
                    required 
                    icon="fas fa-credit-card"
                />

                <x-forms.select 
                    name="status" 
                    label="Account Status" 
                    :options="$statuses" 
                    :selected="old('status', $company->status)" 
                    required
                    icon="fas fa-toggle-on"
                />
            </div>
        </x-forms.card>

        <!-- Address & Remarks Section -->
        <x-forms.card title="Corporate Location & Notes" subtitle="Headquarters physical address and business notes">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="city" 
                    label="City / Headquarters Region" 
                    :value="old('city', $company->city)" 
                    icon="fas fa-city"
                />

                <x-forms.input 
                    name="country" 
                    label="Country" 
                    :value="old('country', $company->country)" 
                    icon="fas fa-flag"
                />

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="address" 
                        label="Full Corporate Street Address" 
                        :value="old('address', $company->address)" 
                        rows="2"
                    />
                </div>

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="notes" 
                        label="Internal Corporate Notes" 
                        :value="old('notes', $company->notes)" 
                        rows="3"
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <x-forms.button href="{{ route('admin.companies.index') }}" variant="secondary">
                Cancel
            </x-forms.button>
            <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                Update Corporate Account
            </x-forms.button>
        </div>

    </x-forms.form>

</div>
@endsection
