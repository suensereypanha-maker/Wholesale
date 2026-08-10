@extends('admin.layout.app')

@section('title', 'Register New B2B Company')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.companies.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Register B2B Corporate Partner</h1>
                <p class="text-xs text-slate-500">Create a new corporate client account for wholesale purchasing & credit term management</p>
            </div>
        </div>
    </div>

    <!-- Form Body Card -->
    <x-forms.form action="{{ route('admin.companies.store') }}" method="POST" class="space-y-6">
        
        <!-- Corporate Identity Section -->
        <x-forms.card title="Company Identity" subtitle="Legal business credentials and industry sector">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="company_code" 
                    label="Company Code" 
                    :value="old('company_code', $suggestedCode)" 
                    required 
                    icon="fas fa-barcode"
                    placeholder="e.g. COMP-1007"
                />

                <x-forms.input 
                    name="name" 
                    label="Company / Corporate Name" 
                    :value="old('name')" 
                    required 
                    icon="fas fa-building"
                    placeholder="e.g. Apex Global Distributors Ltd"
                />

                <x-forms.select 
                    name="industry" 
                    label="Industry Sector" 
                    :options="array_combine($industries, $industries)" 
                    :selected="old('industry', 'Electronics & Hardware Wholesale')" 
                    required
                    icon="fas fa-industry"
                />

                <x-forms.input 
                    name="tax_id" 
                    label="Tax Identification / VAT Registration" 
                    :value="old('tax_id')" 
                    icon="fas fa-file-invoice"
                    placeholder="e.g. US-TX-9982341"
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
                    :value="old('email')" 
                    icon="fas fa-envelope"
                    placeholder="e.g. corporate@apexglobal.com"
                />

                <x-forms.input 
                    name="phone" 
                    label="Corporate Telephone" 
                    :value="old('phone')" 
                    icon="fas fa-phone"
                    placeholder="e.g. +1 (555) 234-8000"
                />

                <x-forms.input 
                    name="website" 
                    type="url" 
                    label="Official Website URL" 
                    :value="old('website')" 
                    icon="fas fa-globe"
                    placeholder="https://apexglobal.com"
                />

                <x-forms.input 
                    name="total_employees" 
                    type="number" 
                    label="Total Company Employees" 
                    :value="old('total_employees', '50')" 
                    icon="fas fa-users"
                    placeholder="e.g. 50"
                />

                <x-forms.input 
                    name="credit_limit" 
                    type="number" 
                    step="0.01" 
                    label="Approved Credit Limit ($)" 
                    :value="old('credit_limit', '100000.00')" 
                    required 
                    icon="fas fa-credit-card"
                    placeholder="100000.00"
                />

                <x-forms.select 
                    name="status" 
                    label="Account Status" 
                    :options="$statuses" 
                    :selected="old('status', 'active')" 
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
                    :value="old('city')" 
                    icon="fas fa-city"
                    placeholder="e.g. Austin"
                />

                <x-forms.input 
                    name="country" 
                    label="Country" 
                    :value="old('country', 'United States')" 
                    icon="fas fa-flag"
                    placeholder="e.g. United States"
                />

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="address" 
                        label="Full Corporate Street Address" 
                        :value="old('address')" 
                        rows="2"
                        placeholder="Suite number, building floor, industrial park..."
                    />
                </div>

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="notes" 
                        label="Internal Corporate Notes" 
                        :value="old('notes')" 
                        rows="3"
                        placeholder="Internal relationship notes, preferred freight providers, executive contacts..."
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <x-forms.button href="{{ route('admin.companies.index') }}" variant="secondary">
                Cancel
            </x-forms.button>
            <x-forms.button type="submit" variant="primary" icon="fas fa-save" class="!bg-indigo-600 hover:!bg-indigo-700">
                Save Corporate Account
            </x-forms.button>
        </div>

    </x-forms.form>

</div>
@endsection
