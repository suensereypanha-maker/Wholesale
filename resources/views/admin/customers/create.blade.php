@extends('admin.layout.app')

@section('title', 'Add New Wholesale Customer')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Register Wholesale Customer</h1>
                <p class="text-xs text-slate-500">Create a new B2B client account with custom wholesale discount rates and credit limits</p>
            </div>
        </div>
    </div>

    <!-- Form Body Card -->
    <x-forms.form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
        
        <!-- General Information Section -->
        <x-forms.card title="Basic Client Information" subtitle="Primary business identity and contact details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="customer_code" 
                    label="Customer Code" 
                    :value="old('customer_code', $suggestedCode)" 
                    required 
                    icon="fas fa-barcode"
                    placeholder="e.g. CUST-1009"
                />

                <x-forms.input 
                    name="name" 
                    label="Primary Contact Name" 
                    :value="old('name')" 
                    required 
                    icon="fas fa-user"
                    placeholder="e.g. Robert Smith"
                />

                <x-forms.input 
                    name="company_name" 
                    label="Company / Business Name" 
                    :value="old('company_name')" 
                    icon="fas fa-building"
                    placeholder="e.g. Apex Tech Distribution Inc."
                />

                <x-forms.input 
                    name="email" 
                    type="email" 
                    label="Email Address" 
                    :value="old('email')" 
                    icon="fas fa-envelope"
                    placeholder="e.g. orders@apextech.com"
                />

                <x-forms.input 
                    name="phone" 
                    label="Phone Number" 
                    :value="old('phone')" 
                    icon="fas fa-phone"
                    placeholder="e.g. +1 (555) 019-2834"
                />

                <x-forms.input 
                    name="tax_id" 
                    label="Tax Identification / VAT Number" 
                    :value="old('tax_id')" 
                    icon="fas fa-file-invoice"
                    placeholder="e.g. US-TAX-9982341"
                />
            </div>
        </x-forms.card>

        <!-- Wholesale Terms & Tiering Section -->
        <x-forms.card title="Wholesale Terms & Tiering" subtitle="Configure wholesale discount rate, credit limits, and payment agreements">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.select 
                    name="tier" 
                    label="Wholesale Tier Level" 
                    :options="$tiers" 
                    :selected="old('tier', 'Standard Wholesale')" 
                    required
                    icon="fas fa-crown"
                />

                <x-forms.input 
                    name="wholesale_discount" 
                    type="number" 
                    step="0.01" 
                    label="Wholesale Discount Rate (%)" 
                    :value="old('wholesale_discount', '10.00')" 
                    required 
                    icon="fas fa-percent"
                    placeholder="10.00"
                />

                <x-forms.input 
                    name="credit_limit" 
                    type="number" 
                    step="0.01" 
                    label="Credit Limit ($)" 
                    :value="old('credit_limit', '25000.00')" 
                    required 
                    icon="fas fa-credit-card"
                    placeholder="25000.00"
                />

                <x-forms.select 
                    name="payment_terms" 
                    label="Payment Terms Agreement" 
                    :options="array_combine($paymentTerms, $paymentTerms)" 
                    :selected="old('payment_terms', 'Net 30')" 
                    required
                    icon="fas fa-handshake"
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
        <x-forms.card title="Location & Operations" subtitle="Physical address and internal order notes">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="city" 
                    label="City / Region" 
                    :value="old('city')" 
                    icon="fas fa-city"
                    placeholder="e.g. Chicago"
                />

                <x-forms.input 
                    name="country" 
                    label="Country" 
                    :value="old('country', 'United States')" 
                    icon="fas fa-globe"
                    placeholder="e.g. United States"
                />

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="address" 
                        label="Full Shipping / Billing Address" 
                        :value="old('address')" 
                        rows="2"
                        placeholder="Street address, building name, suite number..."
                    />
                </div>

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="notes" 
                        label="Special Instructions & Remarks" 
                        :value="old('notes')" 
                        rows="3"
                        placeholder="Internal notes, preferred delivery times, special freight requirements..."
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <x-forms.button href="{{ route('admin.customers.index') }}" variant="secondary">
                Cancel
            </x-forms.button>
            <x-forms.button type="submit" variant="primary" icon="fas fa-save" class="!bg-indigo-600 hover:!bg-indigo-700">
                Save Wholesale Customer
            </x-forms.button>
        </div>

    </x-forms.form>

</div>
@endsection
