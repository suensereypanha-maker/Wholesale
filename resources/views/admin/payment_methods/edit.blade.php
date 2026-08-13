@extends('admin.layout.app')

@section('title', 'Edit Payment Method')

@section('content')
<div class="space-y-6 w-full max-w-4xl mx-auto">

    <!-- Header Navigation Bar -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.payment-methods.index') }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Payment Method</h1>
                <p class="text-xs text-slate-500">Method Code: <span class="font-mono font-bold text-indigo-600">{{ $paymentMethod->code }}</span></p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <x-forms.form action="{{ route('admin.payment-methods.update', $paymentMethod) }}" method="PUT" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Method Code -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Payment Method Code *</label>
                    <input 
                        type="text" 
                        name="code" 
                        value="{{ old('code', $paymentMethod->code) }}" 
                        required 
                        class="w-full text-xs font-mono font-bold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs"
                    />
                    @error('code')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Method Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Payment Method Name *</label>
                    <input 
                        type="text" 
                        name="name" 
                        value="{{ old('name', $paymentMethod->name) }}" 
                        required 
                        class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs"
                    />
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Channel Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Channel Type *</label>
                    <select 
                        name="type" 
                        required 
                        class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs appearance-none"
                    >
                        @foreach($types as $tKey => $tVal)
                            <option value="{{ $tKey }}" {{ old('type', $paymentMethod->type) == $tKey ? 'selected' : '' }}>{{ $tVal }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Status *</label>
                    <select 
                        name="status" 
                        required 
                        class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs appearance-none"
                    >
                        <option value="active" {{ old('status', $paymentMethod->status) == 'active' ? 'selected' : '' }}>Active (Available for transactions)</option>
                        <option value="inactive" {{ old('status', $paymentMethod->status) == 'inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                    </select>
                </div>

                <!-- Account Number -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Account / Card / IBAN #</label>
                    <input 
                        type="text" 
                        name="account_number" 
                        value="{{ old('account_number', $paymentMethod->account_number) }}" 
                        class="w-full text-xs font-mono rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs"
                    />
                </div>

                <!-- Account Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Account / Entity Holder Name</label>
                    <input 
                        type="text" 
                        name="account_name" 
                        value="{{ old('account_name', $paymentMethod->account_name) }}" 
                        class="w-full text-xs font-semibold rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs"
                    />
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Internal Instructions & Payment Remarks</label>
                <textarea 
                    name="notes" 
                    rows="3" 
                    class="w-full text-xs rounded-xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-2.5 px-3.5 bg-white text-slate-900 shadow-2xs"
                >{{ old('notes', $paymentMethod->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <x-forms.button href="{{ route('admin.payment-methods.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-save" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Update Payment Method
                </x-forms.button>
            </div>
        </x-forms.form>
    </div>

</div>
@endsection
