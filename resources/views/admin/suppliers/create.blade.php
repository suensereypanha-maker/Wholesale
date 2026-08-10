@extends('admin.layout.app')

@section('title', 'Register New Supplier')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto w-full">

    <!-- Page Header & Navigation Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.suppliers.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Register Supplier</h1>
                <p class="text-xs text-slate-500">Add a new wholesale vendor, contact representative, and payment terms</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button href="{{ route('admin.suppliers.index') }}" variant="secondary" icon="fas fa-xmark">
                Cancel
            </x-forms.button>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <x-forms.form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-6">

            <!-- Section 1: Basic Supplier Information -->
            <div class="space-y-4">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-building"></i> Basic Vendor Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-forms.input 
                            name="code" 
                            label="Supplier Code" 
                            :value="old('code', $suggestedCode)" 
                            required 
                            placeholder="e.g. SUP-1006"
                            icon="fas fa-barcode"
                        />
                        <p class="text-[11px] text-slate-400 mt-1">Unique identifier code for tracking inventory purchases</p>
                    </div>

                    <div>
                        <x-forms.input 
                            name="name" 
                            label="Contact / Representative Name" 
                            :value="old('name')" 
                            required 
                            placeholder="e.g. John Anderson"
                            icon="fas fa-user"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-forms.input 
                            name="company_name" 
                            label="Company / Enterprise Name" 
                            :value="old('company_name')" 
                            placeholder="e.g. Apex Global Logistics & Components"
                            icon="fas fa-building"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Supply Category</label>
                        <select name="category" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Contact Details -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-address-book"></i> Contact & Communication
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-forms.input 
                            name="email" 
                            type="email"
                            label="Email Address" 
                            :value="old('email')" 
                            placeholder="e.g. sales@vendor.com"
                            icon="fas fa-envelope"
                        />
                    </div>

                    <div>
                        <x-forms.input 
                            name="phone" 
                            label="Phone Number" 
                            :value="old('phone')" 
                            placeholder="e.g. +1 (555) 019-2831"
                            icon="fas fa-phone"
                        />
                    </div>

                    <div>
                        <x-forms.input 
                            name="website" 
                            type="url"
                            label="Website URL" 
                            :value="old('website')" 
                            placeholder="https://vendor.com"
                            icon="fas fa-globe"
                        />
                    </div>
                </div>
            </div>

            <!-- Section 3: Commercial & Payment Terms -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-credit-card"></i> Commercial Terms & Status
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Payment Terms <span class="text-rose-500">*</span></label>
                        <select name="payment_terms" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @foreach($paymentTermsOptions as $term)
                                <option value="{{ $term }}" {{ old('payment_terms', 'Net 30') == $term ? 'selected' : '' }}>{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-forms.input 
                            name="tax_id" 
                            label="Tax ID / VAT Registration" 
                            :value="old('tax_id')" 
                            placeholder="e.g. US-TAX-982104"
                            icon="fas fa-receipt"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Rating (1 to 5 Stars)</label>
                        <select name="rating" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @for($r = 5; $r >= 1; $r--)
                                <option value="{{ $r }}" {{ old('rating', 5) == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Account Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 4: Location & Address -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-location-dot"></i> Address & Facility Location
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <x-forms.input 
                            name="address" 
                            label="Street Address / Facility" 
                            :value="old('address')" 
                            placeholder="e.g. 742 Industrial Tech Parkway, Suite 400"
                            icon="fas fa-map-pin"
                        />
                    </div>

                    <div>
                        <x-forms.input 
                            name="city" 
                            label="City & State" 
                            :value="old('city')" 
                            placeholder="e.g. San Jose, CA"
                            icon="fas fa-city"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-forms.input 
                            name="country" 
                            label="Country" 
                            :value="old('country', 'United States')" 
                            placeholder="e.g. United States"
                            icon="fas fa-flag"
                        />
                    </div>
                </div>
            </div>

            <!-- Section 5: Additional Notes -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <x-forms.textarea 
                    name="notes" 
                    label="Internal Operational Notes" 
                    :value="old('notes')" 
                    placeholder="Provide any specific supplier agreements, audit logs, or notes..."
                    rows="3"
                />
            </div>

            <!-- Actions Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <x-forms.button href="{{ route('admin.suppliers.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Save Supplier Record
                </x-forms.button>
            </div>

        </x-forms.form>
    </div>

</div>
@endsection
