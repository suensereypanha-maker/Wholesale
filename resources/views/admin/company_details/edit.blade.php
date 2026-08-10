@extends('admin.layout.app')

@section('title', 'Edit Platform Company Details')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto w-full">

    <!-- Page Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.company-details.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-base"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Corporate Profile & Bank Details</h1>
                <p class="text-xs text-slate-500">Update platform legal identity, tax registration numbers, and wire transfer bank account information</p>
            </div>
        </div>
    </div>

    <!-- Form Body Card -->
    <x-forms.form action="{{ route('admin.company-details.update') }}" method="PUT" class="space-y-6">
        
        <!-- Corporate Identity Section -->
        <x-forms.card title="Corporate Identity & Registration" subtitle="Legal business name and tax registration information">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="company_name" 
                    label="Platform Trading Name" 
                    :value="old('company_name', $companyDetail->company_name)" 
                    required 
                    icon="fas fa-building"
                />

                <x-forms.input 
                    name="legal_name" 
                    label="Official Registered Legal Name" 
                    :value="old('legal_name', $companyDetail->legal_name)" 
                    icon="fas fa-scale-balanced"
                />

                <x-forms.input 
                    name="tax_number" 
                    label="Tax Identification / VAT Registration Number" 
                    :value="old('tax_number', $companyDetail->tax_number)" 
                    icon="fas fa-file-invoice"
                />

                <x-forms.input 
                    name="registration_number" 
                    label="Business Registration Number" 
                    :value="old('registration_number', $companyDetail->registration_number)" 
                    icon="fas fa-id-card"
                />
            </div>
        </x-forms.card>

        <!-- Official Contact & System Preferences -->
        <x-forms.card title="Contact Channels & Regional Settings" subtitle="Official support communications and currency preferences">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="email" 
                    type="email" 
                    label="Primary Wholesale Sales Email" 
                    :value="old('email', $companyDetail->email)" 
                    icon="fas fa-envelope"
                />

                <x-forms.input 
                    name="support_email" 
                    type="email" 
                    label="Support Communications Email" 
                    :value="old('support_email', $companyDetail->support_email)" 
                    icon="fas fa-headset"
                />

                <x-forms.input 
                    name="phone" 
                    label="Support Telephone Line" 
                    :value="old('phone', $companyDetail->phone)" 
                    icon="fas fa-phone"
                />

                <x-forms.input 
                    name="website" 
                    type="url" 
                    label="Official Portal Website URL" 
                    :value="old('website', $companyDetail->website)" 
                    icon="fas fa-globe"
                />

                <x-forms.select 
                    name="currency" 
                    label="Operating Currency" 
                    :options="$currencies" 
                    :selected="old('currency', $companyDetail->currency)" 
                    required
                    icon="fas fa-coins"
                />

                <x-forms.input 
                    name="timezone" 
                    label="Default Operating Timezone" 
                    :value="old('timezone', $companyDetail->timezone)" 
                    required 
                    icon="fas fa-clock"
                />
            </div>
        </x-forms.card>

        <!-- Wholesale Bank Wire Transfer Section -->
        <x-forms.card title="Wholesale Bank Wire Transfer Account" subtitle="Bank account details printed on wholesale invoices & proforma billing">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="bank_name" 
                    label="Bank Name" 
                    :value="old('bank_name', $companyDetail->bank_name)" 
                    icon="fas fa-building-columns"
                />

                <x-forms.input 
                    name="account_name" 
                    label="Beneficiary Account Name" 
                    :value="old('account_name', $companyDetail->account_name)" 
                    icon="fas fa-user-gear"
                />

                <x-forms.input 
                    name="account_number" 
                    label="Account / Routing Number" 
                    :value="old('account_number', $companyDetail->account_number)" 
                    icon="fas fa-hashtag"
                />

                <x-forms.input 
                    name="swift_code" 
                    label="SWIFT / BIC Code" 
                    :value="old('swift_code', $companyDetail->swift_code)" 
                    icon="fas fa-globe"
                />

                <div class="md:col-span-2">
                    <x-forms.input 
                        name="iban" 
                        label="International Bank Account Number (IBAN)" 
                        :value="old('iban', $companyDetail->iban)" 
                        icon="fas fa-barcode"
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Physical Location & Description -->
        <x-forms.card title="Headquarters Address & Description" subtitle="Physical address and platform business summary">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input 
                    name="city" 
                    label="City" 
                    :value="old('city', $companyDetail->city)" 
                    icon="fas fa-city"
                />

                <x-forms.input 
                    name="state" 
                    label="State / Province" 
                    :value="old('state', $companyDetail->state)" 
                    icon="fas fa-map-pin"
                />

                <x-forms.input 
                    name="postal_code" 
                    label="Postal / Zip Code" 
                    :value="old('postal_code', $companyDetail->postal_code)" 
                    icon="fas fa-mail-bulk"
                />

                <x-forms.input 
                    name="country" 
                    label="Country" 
                    :value="old('country', $companyDetail->country)" 
                    icon="fas fa-flag"
                />

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="address" 
                        label="Full Headquarters Address" 
                        :value="old('address', $companyDetail->address)" 
                        rows="2"
                    />
                </div>

                <div class="md:col-span-2">
                    <x-forms.textarea 
                        name="description" 
                        label="Platform Business Description" 
                        :value="old('description', $companyDetail->description)" 
                        rows="3"
                    />
                </div>
            </div>
        </x-forms.card>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <x-forms.button href="{{ route('admin.company-details.index') }}" variant="secondary">
                Cancel
            </x-forms.button>
            <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                Update Corporate Details
            </x-forms.button>
        </div>

    </x-forms.form>

</div>
@endsection
