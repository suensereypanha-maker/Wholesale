@extends('admin.layout.app')

@section('title', 'Edit Wholesale Customer - ' . $customer->name)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Customer: {{ $customer->name }}</h1>
                <p class="text-xs text-slate-500">Update account preferences, wholesale discount tier, and credit terms for {{ $customer->customer_code }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button 
                href="{{ route('admin.customers.show', $customer) }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-eye"
            >
                View Profile
            </x-forms.button>
        </div>
    </div>

    <!-- Form Body Card -->
    <x-forms.form action="{{ route('admin.customers.update', $customer) }}" method="PUT" class="space-y-6">
        
        <!-- Basic Information Section -->
        <x-forms.card title="Basic Client Information" subtitle="Primary business identity and contact details">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="customer_code" 
                    label="Customer Code" 
                    :value="old('customer_code', $customer->customer_code)" 
                    required 
                    icon="fas fa-barcode"
                />

                <x-forms.input 
                    name="name" 
                    label="Primary Contact Name" 
                    :value="old('name', $customer->name)" 
                    required 
                    icon="fas fa-user"
                />

                <x-forms.input 
                    name="company_name" 
                    label="Company / Business Name" 
                    :value="old('company_name', $customer->company_name)" 
                    icon="fas fa-building"
                />

                <x-forms.input 
                    name="email" 
                    type="email" 
                    label="Email Address" 
                    :value="old('email', $customer->email)" 
                    icon="fas fa-envelope"
                />

                <x-forms.input 
                    name="phone" 
                    label="Phone Number" 
                    :value="old('phone', $customer->phone)" 
                    icon="fas fa-phone"
                />

                <x-forms.input 
                    name="tax_id" 
                    label="Tax Identification / VAT Number" 
                    :value="old('tax_id', $customer->tax_id)" 
                    icon="fas fa-file-invoice"
                />
            </div>
        </x-forms.card>

        <!-- Wholesale Terms Section -->
        <x-forms.card title="Wholesale Terms & Tiering" subtitle="Configure wholesale discount rate, credit limits, and payment agreements">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.select 
                    name="tier" 
                    label="Wholesale Tier Level" 
                    :options="$tiers" 
                    :selected="old('tier', $customer->tier)" 
                    required
                    icon="fas fa-crown"
                />

                <x-forms.input 
                    name="wholesale_discount" 
                    type="number" 
                    step="0.01" 
                    label="Wholesale Discount Rate (%)" 
                    :value="old('wholesale_discount', $customer->wholesale_discount)" 
                    required 
                    icon="fas fa-percent"
                />

                <x-forms.input 
                    name="credit_limit" 
                    type="number" 
                    step="0.01" 
                    label="Credit Limit ($)" 
                    :value="old('credit_limit', $customer->credit_limit)" 
                    required 
                    icon="fas fa-credit-card"
                />

                <x-forms.select 
                    name="payment_terms" 
                    label="Payment Terms Agreement" 
                    :options="array_combine($paymentTerms, $paymentTerms)" 
                    :selected="old('payment_terms', $customer->payment_terms)" 
                    required
                    icon="fas fa-handshake"
                />

                <x-forms.select 
                    name="status" 
                    label="Account Status" 
                    :options="$statuses" 
                    :selected="old('status', $customer->status)" 
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
                    :value="old('city', $customer->city)" 
                    icon="fas fa-city"
                />

                <x-forms.input 
                    name="country" 
                    label="Country" 
                    :value="old('country', $customer->country)" 
                    icon="fas fa-globe"
                />

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="address" 
                        label="Full Shipping / Billing Address" 
                        :value="old('address', $customer->address)" 
                        rows="2"
                    />
                </div>

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="notes" 
                        label="Special Instructions & Remarks" 
                        :value="old('notes', $customer->notes)" 
                        rows="3"
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <x-forms.button href="{{ route('admin.customers.index') }}" variant="secondary">
                Cancel
            </x-forms.button>
            <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                Update Wholesale Customer
            </x-forms.button>
        </div>

    </x-forms.form>

</div>
@endsection
