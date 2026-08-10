@extends('admin.layout.app')

@section('title', 'Warehouse Management')

@section('content')
<div class="space-y-6 w-full">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-warehouse text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Warehouse & Logistics Hubs</h1>
                    <p class="text-xs text-slate-500">Manage enterprise fulfillment centers, capacity allocations, and regional depots</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.warehouses.create') }}" 
                variant="primary" 
                icon="fas fa-plus"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Add New Warehouse
            </x-forms.button>
        </div>
    </div>

    <!-- Overview Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-building text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Hubs</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $totalWarehouses }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-[#10b981] fa-circle-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Warehouses</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $activeWarehouses }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Storage Capacity</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalCapacity) }} <span class="text-xs font-normal text-slate-500">units</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-truck-ramp-box text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Quick Actions</p>
                <a href="{{ route('admin.stocks.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                    Manage Inventory Stock <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.warehouses.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-64">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search warehouse code, name, location..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Results
                </x-forms.button>
                @if(request()->anyFilled(['search', 'status', 'type']))
                    <x-forms.button href="{{ route('admin.warehouses.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Warehouses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 w-full">
        @forelse ($warehouses as $warehouse)
            @php
                $statusColors = [
                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                ];
                $usagePercent = $warehouse->capacity_usage_percent;
            @endphp
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between h-full group">
                <div>
                    <!-- Header with Code & Status Badge -->
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 font-mono font-bold text-xs rounded-lg border border-indigo-100">
                                {{ $warehouse->code }}
                            </span>
                            <span class="text-xs font-medium text-slate-400">• {{ $warehouse->type }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold border capitalize {{ $statusColors[$warehouse->status] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $warehouse->status }}
                        </span>
                    </div>

                    <!-- Warehouse Title & Location -->
                    <h3 class="text-base font-bold text-slate-900 mb-1 leading-snug group-hover:text-indigo-600 transition-colors">
                        <a href="{{ route('admin.warehouses.show', $warehouse) }}">
                            {{ $warehouse->name }}
                        </a>
                    </h3>
                    <p class="text-xs text-slate-500 mb-4 flex items-start gap-1.5">
                        <i class="fas fa-location-dot text-slate-400 mt-0.5 flex-shrink-0"></i>
                        <span class="line-clamp-2">{{ $warehouse->location }}</span>
                    </p>

                    <!-- Storage Usage Progress Bar -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 mb-4 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">Storage Usage</span>
                            <span class="font-bold text-slate-900">{{ number_format($warehouse->total_quantity) }} / {{ number_format($warehouse->capacity) }} units ({{ $usagePercent }}%)</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $usagePercent > 85 ? 'bg-rose-500' : ($usagePercent > 60 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ $usagePercent }}%"></div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    @if($warehouse->contact_name || $warehouse->contact_phone)
                        <div class="text-xs text-slate-500 space-y-1 mb-4">
                            @if($warehouse->contact_name)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-user-tie text-slate-400 w-4 text-center"></i>
                                    <span>{{ $warehouse->contact_name }}</span>
                                </div>
                            @endif
                            @if($warehouse->contact_phone)
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-slate-400 w-4 text-center"></i>
                                    <span>{{ $warehouse->contact_phone }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Footer Card Action Buttons -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                    <span class="text-xs font-semibold text-slate-500">
                        <strong class="text-slate-900">{{ $warehouse->stocks_count }}</strong> SKU Line Items
                    </span>
                    <div class="flex items-center gap-1.5">
                        <x-forms.button 
                            href="{{ route('admin.warehouses.show', $warehouse) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-eye"
                            title="View Inventory Details" 
                        />

                        <x-forms.button 
                            href="{{ route('admin.warehouses.edit', $warehouse) }}" 
                            variant="ghost" 
                            size="sm" 
                            icon="fas fa-pen-to-square"
                            title="Edit Warehouse" 
                        />

                        <x-forms.form 
                            action="{{ route('admin.warehouses.destroy', $warehouse) }}" 
                            method="DELETE" 
                            class="inline-block !space-y-0"
                            onsubmit="return confirm('Are you sure you want to delete warehouse {{ $warehouse->name }}?');"
                        >
                            <x-forms.button 
                                type="submit" 
                                variant="ghost" 
                                size="sm" 
                                icon="fas fa-trash-can" 
                                class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                title="Delete Warehouse"
                            />
                        </x-forms.form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-slate-200/80 text-slate-400 space-y-3">
                <i class="fas fa-warehouse text-3xl text-slate-300"></i>
                <p class="text-sm font-medium text-slate-600">No warehouse logistics hubs found.</p>
                <p class="text-xs text-slate-400">Try adjusting your filters or click below to add your first warehouse hub.</p>
                <div class="pt-2">
                    <x-forms.button href="{{ route('admin.warehouses.create') }}" variant="primary" icon="fas fa-plus">
                        Add Warehouse Hub
                    </x-forms.button>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
