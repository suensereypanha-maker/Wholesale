@extends('admin.layout.app')

@section('title', 'Edit Warehouse - ' . $warehouse->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header & Back Action -->
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-warehouse text-xl"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Warehouse: {{ $warehouse->name }}</h1>
                <p class="text-xs text-slate-500">Update capacity, location, and operational contact details for {{ $warehouse->code }}</p>
            </div>
        </div>
        <x-forms.button href="{{ route('admin.warehouses.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back to Warehouses
        </x-forms.button>
    </div>

    <!-- Edit Form Card -->
    <x-forms.card title="Update Facility Details" subtitle="Modify operational configuration for {{ $warehouse->code }}">
        <x-forms.form action="{{ route('admin.warehouses.update', $warehouse) }}" method="PUT">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Warehouse Code -->
                <x-forms.input 
                    name="code" 
                    label="Warehouse Code / Identifier" 
                    :value="old('code', $warehouse->code)" 
                    required 
                />

                <!-- Warehouse Name -->
                <x-forms.input 
                    name="name" 
                    label="Facility / Warehouse Name" 
                    :value="old('name', $warehouse->name)" 
                    required 
                />

                <!-- Warehouse Type -->
                <x-forms.select 
                    name="type" 
                    label="Warehouse Type" 
                    required 
                >
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ old('type', $warehouse->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </x-forms.select>

                <!-- Total Capacity -->
                <x-forms.input 
                    type="number" 
                    name="capacity" 
                    label="Storage Capacity (Units)" 
                    :value="old('capacity', $warehouse->capacity)" 
                    required 
                />

                <!-- Contact Name -->
                <x-forms.input 
                    name="contact_name" 
                    label="Primary Operations Manager" 
                    :value="old('contact_name', $warehouse->contact_name)" 
                />

                <!-- Contact Phone -->
                <x-forms.input 
                    name="contact_phone" 
                    label="Contact Phone Number" 
                    :value="old('contact_phone', $warehouse->contact_phone)" 
                />

                <!-- Contact Email -->
                <x-forms.input 
                    type="email" 
                    name="contact_email" 
                    label="Contact Email Address" 
                    :value="old('contact_email', $warehouse->contact_email)" 
                />

                <!-- Status -->
                <x-forms.select 
                    name="status" 
                    label="Operational Status" 
                    required 
                >
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $warehouse->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Full Location Address -->
            <x-forms.textarea 
                name="location" 
                label="Full Address & Geographic Location" 
                rows="2" 
                required 
            >{{ old('location', $warehouse->location) }}</x-forms.textarea>

            <!-- Operational Notes -->
            <x-forms.textarea 
                name="notes" 
                label="Internal Operational Notes & Guidelines" 
                rows="3" 
            >{{ old('notes', $warehouse->notes) }}</x-forms.textarea>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <x-forms.button href="{{ route('admin.warehouses.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-check">
                    Save Changes
                </x-forms.button>
            </div>
        </x-forms.form>
    </x-forms.card>

</div>
@endsection
