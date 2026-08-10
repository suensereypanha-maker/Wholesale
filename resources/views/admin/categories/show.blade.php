@extends('admin.layout.app')

@section('title', 'Supply Category - ' . $category->name)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="flex items-center gap-3">
                <span class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                    <i class="{{ $category->icon ?? 'fas fa-microchip' }}"></i>
                </span>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $category->name }}</h1>
                        <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono font-bold text-xs rounded-lg border border-indigo-100">
                            {{ $category->code }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border capitalize {{ $category->status == 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ $category->status }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $category->type }} • Created {{ $category->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-forms.button href="{{ route('admin.categories.edit', $category) }}" variant="secondary" icon="fas fa-pen-to-square">
                Edit Category
            </x-forms.button>

            <x-forms.form 
                action="{{ route('admin.categories.destroy', $category) }}" 
                method="DELETE" 
                class="inline-block !space-y-0"
                onsubmit="return confirm('Are you sure you want to delete category {{ $category->name }}?');"
            >
                <x-forms.button 
                    type="submit" 
                    variant="danger" 
                    icon="fas fa-trash-can"
                >
                    Delete
                </x-forms.button>
            </x-forms.form>
        </div>
    </div>

    <!-- Category Profile Content -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fas fa-circle-info"></i> Category Overview & Specifications
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Category Type</span>
                <span class="text-sm font-bold text-slate-900 mt-1 block">{{ $category->type }}</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">URL Slug</span>
                <span class="text-sm font-mono font-bold text-indigo-600 mt-1 block">{{ $category->slug }}</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Sort Order</span>
                <span class="text-sm font-bold text-slate-900 mt-1 block">#{{ $category->order }}</span>
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Classification Description</h3>
            <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 min-h-[90px]">
                {{ $category->description ?? 'No description provided.' }}
            </p>
        </div>
    </div>

</div>
@endsection
