@extends('admin.layout.app')

@section('title', 'Create Supply Category')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto w-full">

    <!-- Page Header & Navigation Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Create Supply Category</h1>
                <p class="text-xs text-slate-500">Define new computer hardware, laptop part, or RAM/CPU supply classification</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button href="{{ route('admin.categories.index') }}" variant="secondary" icon="fas fa-xmark">
                Cancel
            </x-forms.button>
        </div>
    </div>

    <!-- Form Container Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <x-forms.form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">

            <!-- Section 1: Basic Information -->
            <div class="space-y-4">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-layer-group"></i> Category Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-forms.input 
                            name="code" 
                            label="Category Code" 
                            :value="old('code', $suggestedCode)" 
                            required 
                            placeholder="e.g. CAT-RAM, CAT-CPU"
                            icon="fas fa-barcode"
                        />
                    </div>

                    <div>
                        <x-forms.input 
                            name="name" 
                            label="Category Name" 
                            :value="old('name')" 
                            required 
                            placeholder="e.g. RAM & Memory Modules"
                            icon="fas fa-tag"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Category Type <span class="text-rose-500">*</span></label>
                        <select name="type" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @foreach($types as $t)
                                <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Category Icon <span class="text-rose-500">*</span></label>
                        <select name="icon" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @foreach($icons as $class => $label)
                                <option value="{{ $class }}" {{ old('icon') == $class ? 'selected' : '' }}>{{ $label }} ({{ $class }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Display & Status -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-2">
                    <i class="fas fa-sliders"></i> Display & Configuration
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                            @foreach($statuses as $val => $label)
                                <option value="{{ $val }}" {{ old('status', 'active') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-forms.input 
                            name="order" 
                            type="number"
                            label="Sort Order" 
                            :value="old('order', 0)" 
                            placeholder="0"
                            icon="fas fa-sort"
                        />
                    </div>
                </div>

                <div>
                    <x-forms.textarea 
                        name="description" 
                        label="Description & Hardware Details" 
                        :value="old('description')" 
                        placeholder="Provide details about what materials or computer components belong to this supply category..."
                        rows="3"
                    />
                </div>
            </div>

            <!-- Actions Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <x-forms.button href="{{ route('admin.categories.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Save Supply Category
                </x-forms.button>
            </div>

        </x-forms.form>
    </div>

</div>
@endsection
