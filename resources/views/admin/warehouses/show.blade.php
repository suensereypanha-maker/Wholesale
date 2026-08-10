@extends('admin.layout.app')

@section('title', 'Warehouse Hub - ' . $warehouse->name)

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
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 font-mono font-bold text-xs rounded-md">
                            {{ $warehouse->code }}
                        </span>
                        <span class="capitalize text-xs font-semibold px-2 py-0.5 rounded-full border {{ $warehouse->status === 'active' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-700' }}">
                            {{ $warehouse->status }}
                        </span>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $warehouse->name }}</h1>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.stocks.create', ['warehouse_id' => $warehouse->id]) }}" 
                variant="primary" 
                icon="fas fa-plus"
            >
                Add Inventory SKU
            </x-forms.button>
            <x-forms.button 
                href="{{ route('admin.warehouses.edit', $warehouse) }}" 
                variant="secondary" 
                icon="fas fa-pen-to-square"
            >
                Edit Facility
            </x-forms.button>
            <x-forms.button 
                href="{{ route('admin.warehouses.index') }}" 
                variant="ghost" 
                icon="fas fa-arrow-left"
            >
                Back
            </x-forms.button>
        </div>
    </div>

    <!-- Summary Metrics Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Stored Units</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($warehouse->total_quantity) }}</h3>
            <p class="text-xs text-slate-400 mt-1">Across {{ $stocks->count() }} SKU line items</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Capacity Utilization</p>
            <h3 class="text-2xl font-extrabold text-indigo-600">{{ $warehouse->capacity_usage_percent }}%</h3>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2">
                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ $warehouse->capacity_usage_percent }}%"></div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Stock Valuation</p>
            <h3 class="text-2xl font-extrabold text-emerald-600">${{ number_format($warehouse->total_valuation, 2) }}</h3>
            <p class="text-xs text-slate-400 mt-1">Asset valuation at cost</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Facility Type</p>
            <h3 class="text-lg font-bold text-slate-900 truncate">{{ $warehouse->type }}</h3>
            <p class="text-xs text-slate-400 mt-1">Capacity: {{ number_format($warehouse->capacity) }} units</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Warehouse Profile Info -->
        <div class="space-y-6">
            <x-forms.card title="Facility Information" subtitle="Physical address & operational contacts">
                <div class="space-y-4 text-xs text-slate-700">
                    <div>
                        <span class="text-slate-400 block font-medium">Full Address:</span>
                        <p class="font-semibold text-slate-900 mt-0.5 leading-relaxed">{{ $warehouse->location }}</p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 grid grid-cols-1 gap-3">
                        <div>
                            <span class="text-slate-400 block font-medium">Operations Manager:</span>
                            <p class="font-semibold text-slate-900">{{ $warehouse->contact_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Contact Phone:</span>
                            <p class="font-semibold text-slate-900">{{ $warehouse->contact_phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Contact Email:</span>
                            <p class="font-semibold text-slate-900">{{ $warehouse->contact_email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($warehouse->notes)
                        <div class="pt-3 border-t border-slate-100">
                            <span class="text-slate-400 block font-medium">Operational Notes:</span>
                            <p class="text-slate-600 italic mt-0.5">{{ $warehouse->notes }}</p>
                        </div>
                    @endif
                </div>
            </x-forms.card>
        </div>

        <!-- Right Column: Warehouse Stock Inventory List Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Stored Inventory Items</h2>
                        <p class="text-xs text-slate-500">Live inventory line items hosted at this facility</p>
                    </div>
                    <x-forms.button 
                        href="{{ route('admin.stocks.create', ['warehouse_id' => $warehouse->id]) }}" 
                        variant="primary" 
                        size="sm" 
                        icon="fas fa-plus"
                    >
                        Add Stock
                    </x-forms.button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="px-5 py-3">SKU & Product</th>
                                <th class="px-5 py-3">Location</th>
                                <th class="px-5 py-3 text-right">Qty / Status</th>
                                <th class="px-5 py-3 text-right">Unit Cost</th>
                                <th class="px-5 py-3 text-right">Line Value</th>
                                <th class="px-5 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($stocks as $stock)
                                @php
                                    $badge = match($stock->computed_status) {
                                        'low_stock' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'out_of_stock' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'overstocked' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="font-mono text-[11px] font-bold text-indigo-600">{{ $stock->sku }}</div>
                                        <div class="font-bold text-slate-900 text-xs hover:text-indigo-600">
                                            <a href="{{ route('admin.stocks.show', $stock) }}">{{ $stock->product_name }}</a>
                                        </div>
                                        <div class="text-[10px] text-slate-400">{{ $stock->category }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-slate-600">
                                        {{ $stock->rack_location ?? 'Unassigned' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="font-bold text-slate-900">{{ number_format($stock->quantity) }} units</div>
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold border capitalize mt-0.5 {{ $badge }}">
                                            {{ str_replace('_', ' ', $stock->computed_status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-semibold text-slate-700">
                                        ${{ number_format($stock->unit_cost, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                        ${{ number_format($stock->total_value, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <x-forms.button 
                                                href="{{ route('admin.stocks.show', $stock) }}" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-eye"
                                            />
                                            <x-forms.button 
                                                href="{{ route('admin.stocks.edit', $stock) }}" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-pen-to-square"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                        No inventory stock items currently assigned to this warehouse facility.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
