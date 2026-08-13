@extends('admin.layout.app')

@section('title', 'Payment Method - ' . $paymentMethod->name)

@section('content')
<div class="space-y-6 w-full max-w-4xl mx-auto">

    <!-- Header Navigation Bar -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.payment-methods.index') }}" class="p-2 bg-slate-100 text-slate-600 hover:bg-slate-200 rounded-xl transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $paymentMethod->name }}</h1>
                <p class="text-xs text-slate-500">Method Code: <span class="font-mono font-bold text-indigo-600">{{ $paymentMethod->code }}</span></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button href="{{ route('admin.payment-methods.edit', $paymentMethod) }}" variant="primary" icon="fas fa-pen-to-square" class="!bg-indigo-600 hover:!bg-indigo-700">
                Edit Channel
            </x-forms.button>
        </div>
    </div>

    <!-- Details Card -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex justify-between items-start border-b border-slate-100 pb-6">
            <div class="space-y-1">
                <span class="px-3 py-1 rounded-full text-xs font-bold border capitalize {{ $paymentMethod->type_badge }}">
                    {{ ucfirst($paymentMethod->type) }} Channel
                </span>
                <h2 class="text-lg font-bold text-slate-900 pt-2">{{ $paymentMethod->name }}</h2>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold border capitalize {{ $paymentMethod->status_badge }}">
                Status: {{ $paymentMethod->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-200/60">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Account / Entity Holder Name</p>
                <p class="text-sm font-bold text-slate-900">{{ $paymentMethod->account_name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Account / Card / IBAN Number</p>
                <p class="text-sm font-mono font-bold text-indigo-700">{{ $paymentMethod->account_number ?? 'N/A' }}</p>
            </div>
        </div>

        @if($paymentMethod->notes)
            <div>
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Instructions & Notes</h3>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 font-mono whitespace-pre-line">
                    {{ $paymentMethod->notes }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
