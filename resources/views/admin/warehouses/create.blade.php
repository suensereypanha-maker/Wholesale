@extends('admin.layout.app')

@section('title', 'Add Warehouse Hub')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header & Back Action -->
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-warehouse text-xl"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Add New Logistics Warehouse</h1>
                <p class="text-xs text-slate-500">Register a new storage depot or regional fulfillment facility</p>
            </div>
        </div>
        <x-forms.button href="{{ route('admin.warehouses.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back to Warehouses
        </x-forms.button>
    </div>

    <!-- Create Form Card -->
    <x-forms.card title="Warehouse Information" subtitle="Enter structural, capacity, and operational contact details">
        <x-forms.form action="{{ route('admin.warehouses.store') }}" method="POST">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Warehouse Code -->
                <x-forms.input 
                    name="code" 
                    label="Warehouse Code / SKU Identifier" 
                    placeholder="e.g. WH-PHN-005" 
                    :value="old('code')" 
                    required 
                    helper="Unique facility identification code"
                />

                <!-- Warehouse Name -->
                <x-forms.input 
                    name="name" 
                    label="Facility / Warehouse Name" 
                    placeholder="e.g. Phnom Penh Central Hub" 
                    :value="old('name')" 
                    required 
                />

                <!-- Warehouse Type -->
                <x-forms.select 
                    name="type" 
                    label="Warehouse Type" 
                    required 
                >
                    <option value="">Select Facility Type</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </x-forms.select>

                <!-- Total Capacity -->
                <x-forms.input 
                    type="number" 
                    name="capacity" 
                    label="Storage Capacity (Units)" 
                    placeholder="e.g. 20000" 
                    :value="old('capacity', 10000)" 
                    required 
                />

                <!-- Contact Name -->
                <x-forms.input 
                    name="contact_name" 
                    label="Primary Operations Manager" 
                    placeholder="e.g. Sokha Chan" 
                    :value="old('contact_name')" 
                />

                <!-- Contact Phone -->
                <x-forms.input 
                    name="contact_phone" 
                    label="Contact Phone Number" 
                    placeholder="e.g. +855 23 888 101" 
                    :value="old('contact_phone')" 
                />

                <!-- Contact Email -->
                <x-forms.input 
                    type="email" 
                    name="contact_email" 
                    label="Contact Email Address" 
                    placeholder="manager@b2bwholesale.com" 
                    :value="old('contact_email')" 
                />

                <!-- Status -->
                <x-forms.select 
                    name="status" 
                    label="Operational Status" 
                    required 
                >
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', 'active') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Full Location Address -->
            <x-forms.textarea 
                name="location" 
                label="Full Address & Geographic Location" 
                placeholder="Enter complete address, district, province, and transport accessibility notes..." 
                rows="2" 
                required 
            >{{ old('location') }}</x-forms.textarea>

            <!-- Operational Notes -->
            <x-forms.textarea 
                name="notes" 
                label="Internal Operational Notes & Guidelines" 
                placeholder="Special storage constraints, cold chain availability, loading docks, etc..." 
                rows="3" 
            >{{ old('notes') }}</x-forms.textarea>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <x-forms.button href="{{ route('admin.warehouses.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-check">
                    Create Warehouse Hub
                </x-forms.button>
            </div>
        </x-forms.form>
    </x-forms.card>

</div>
@endsection
