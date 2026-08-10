@extends('admin.layout.app')

@section('title', 'Supplier Directory')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-truck-field text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Suppliers & Vendors</h1>
                    <p class="text-xs text-slate-500">Manage global manufacturers, distributors, payment terms, and vendor contacts</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.suppliers.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add New Supplier
            </x-forms.button>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-truck-field text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Vendors</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalSuppliers) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Suppliers</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($activeSuppliers) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pending Audit</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($pendingSuppliers) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Supply Categories</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($categoriesCount) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.suppliers.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search supplier code, name, company, email..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="category" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Audit</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'status', 'category']))
                    <x-forms.button href="{{ route('admin.suppliers.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Suppliers Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Supplier Code</th>
                        <th class="py-3.5 px-5">Name & Company</th>
                        <th class="py-3.5 px-5">Category</th>
                        <th class="py-3.5 px-5">Contact Details</th>
                        <th class="py-3.5 px-5">Payment Terms</th>
                        <th class="py-3.5 px-5">Rating</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Code -->
                            <td class="py-4 px-5">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold rounded-lg border border-indigo-100 text-[11px]">
                                    {{ $supplier->code }}
                                </span>
                            </td>

                            <!-- Name & Company -->
                            <td class="py-4 px-5">
                                <div class="font-bold text-slate-900 group-hover:text-indigo-600">
                                    <a href="{{ route('admin.suppliers.show', $supplier) }}" class="hover:underline">
                                        {{ $supplier->name }}
                                    </a>
                                </div>
                                @if($supplier->company_name)
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-building text-slate-400"></i>
                                        <span>{{ $supplier->company_name }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    <i class="fas fa-tag text-[10px] text-slate-400"></i>
                                    {{ $supplier->category ?? 'General' }}
                                </span>
                            </td>

                            <!-- Contact -->
                            <td class="py-4 px-5">
                                <div class="space-y-0.5">
                                    @if($supplier->email)
                                        <div class="text-slate-700 flex items-center gap-1.5">
                                            <i class="fas fa-envelope text-slate-400 w-3.5 text-center"></i>
                                            <span>{{ $supplier->email }}</span>
                                        </div>
                                    @endif
                                    @if($supplier->phone)
                                        <div class="text-slate-500 text-[11px] flex items-center gap-1.5">
                                            <i class="fas fa-phone text-slate-400 w-3.5 text-center"></i>
                                            <span>{{ $supplier->phone }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Payment Terms -->
                            <td class="py-4 px-5 font-semibold text-slate-700">
                                {{ $supplier->payment_terms }}
                            </td>

                            <!-- Rating -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-0.5 text-amber-400 text-[11px]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $supplier->rating ? '' : 'text-slate-200' }}"></i>
                                    @endfor
                                    <span class="ml-1 text-slate-600 font-bold text-xs">({{ $supplier->rating }})</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-5">
                                @php
                                    $badgeClasses = [
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border capitalize {{ $badgeClasses[$supplier->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ $supplier->status }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <x-forms.button 
                                        href="{{ route('admin.suppliers.show', $supplier) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Supplier Profile" 
                                    />
                                    <x-forms.button 
                                        href="{{ route('admin.suppliers.edit', $supplier) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-pen-to-square"
                                        title="Edit Supplier" 
                                    />
                                    <x-forms.form 
                                        action="{{ route('admin.suppliers.destroy', $supplier) }}" 
                                        method="DELETE" 
                                        class="inline-block !space-y-0"
                                        onsubmit="return confirm('Are you sure you want to delete supplier {{ $supplier->name }}?');"
                                    >
                                        <x-forms.button 
                                            type="submit" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon="fas fa-trash-can" 
                                            class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                            title="Delete Supplier"
                                        />
                                    </x-forms.form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-truck-field text-3xl text-slate-300"></i>
                                <p class="text-sm font-medium text-slate-600">No suppliers found in directory.</p>
                                <p class="text-xs text-slate-400">Try adjusting your filters or click below to register a new vendor.</p>
                                <div class="pt-2">
                                    <x-forms.button href="{{ route('admin.suppliers.create') }}" variant="primary" icon="fas fa-plus">
                                        Add Supplier
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
