@extends('admin.layout.app')

@section('title', 'Supply Categories')

@section('content')
<div class="space-y-6 w-full">

    <!-- Flash Success Notification -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <i class="fas fa-circle-check text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-layer-group text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Supply & Hardware Categories</h1>
                    <p class="text-xs text-slate-500">Manage computer components, RAM, CPUs, GPUs, SSDs, and laptop repair material classifications</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.categories.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add Supply Category
            </x-forms.button>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-microchip text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Categories</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalCategories) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Categories</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($activeCategories) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Hardware Types</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($typesCount) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search category code, name, description..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="type" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Types</option>
                        @foreach($types as $t)
                            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'status', 'type']))
                    <x-forms.button href="{{ route('admin.categories.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Category Grid Display -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($categories as $category)
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <!-- Header with Icon, Code & Status -->
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base font-bold group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <i class="{{ $category->icon ?? 'fas fa-microchip' }}"></i>
                            </span>
                            <div>
                                <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono font-bold text-[11px] rounded-md border border-indigo-100">
                                    {{ $category->code }}
                                </span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold border capitalize {{ $category->status == 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ $category->status }}
                        </span>
                    </div>

                    <!-- Category Title & Type -->
                    <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug group-hover:text-indigo-600 transition-colors">
                        <a href="{{ route('admin.categories.show', $category) }}">
                            {{ $category->name }}
                        </a>
                    </h3>
                    <div class="mb-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                            <i class="fas fa-tag text-[9px] text-slate-400"></i>
                            {{ $category->type }}
                        </span>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                        {{ $category->description ?? 'No detailed description provided for this hardware classification.' }}
                    </p>
                </div>

                <!-- Footer Card Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto text-xs">
                    <span class="text-[11px] font-medium text-slate-400 font-mono">
                        slug: {{ $category->slug }}
                    </span>
                    <div class="flex items-center gap-1">
                        <x-forms.button 
                            href="{{ route('admin.categories.show', $category) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-eye"
                            title="View Category Details" 
                        />
                        <x-forms.button 
                            href="{{ route('admin.categories.edit', $category) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-pen-to-square"
                            title="Edit Category" 
                        />
                        <x-forms.form 
                            action="{{ route('admin.categories.destroy', $category) }}" 
                            method="DELETE" 
                            class="inline-block !space-y-0"
                            onsubmit="return confirm('Are you sure you want to delete category {{ $category->name }}?');"
                        >
                            <x-forms.button 
                                type="submit" 
                                variant="ghost" 
                                size="sm" 
                                icon="fas fa-trash-can" 
                                class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                title="Delete Category"
                            />
                        </x-forms.form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200/80 text-slate-400 space-y-3">
                <i class="fas fa-layer-group text-3xl text-slate-300"></i>
                <p class="text-sm font-medium text-slate-600">No supply categories found.</p>
                <p class="text-xs text-slate-400">Try adjusting your search criteria or click below to create a new category.</p>
                <div class="pt-2">
                    <x-forms.button href="{{ route('admin.categories.create') }}" variant="primary" icon="fas fa-plus">
                        Add Category
                    </x-forms.button>
                </div>
            </div>
        @endforelse
    </div>

    @if($categories->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            {{ $categories->links() }}
        </div>
    @endif

</div>
@endsection
