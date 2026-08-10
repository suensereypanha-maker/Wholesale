@extends('admin.layout.app')

@section('title', 'Stock Out - Inventory Dispatch')

@section('content')
<div class="space-y-6 w-full" x-data="{ dispatchModalOpen: false, selectedStockId: '' }">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-rose-50 text-rose-600 rounded-xl">
                    <i class="fas fa-arrow-up-from-bracket text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Stock Out (Inventory Outflow & Dispatch)</h1>
                    <p class="text-xs text-slate-500">Record customer order shipments, wholesale dispatches, outward transfers, and stock fulfillment</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                type="button"
                @click="dispatchModalOpen = true" 
                variant="primary" 
                icon="fas fa-minus"
                class="!bg-rose-600 hover:!bg-rose-700"
            >
                New Stock Out Dispatch
            </x-forms.button>
        </div>
    </div>

    <!-- Overview Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Warehouse Stock</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalStockUnits) }} <span class="text-xs font-normal text-slate-500">units</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Low Stock Outflow Risk</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-500">items</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-ban text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Out Of Stock</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $outOfStockCount }} <span class="text-xs font-normal text-slate-500">depleted</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-truck-fast text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Warehouse Value</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($totalValuation, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.stocks.out') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-72">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search SKU, product name..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-56">
                    <select name="warehouse_id" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                        <option value="">All Dispatch Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->code }} - {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Stock Out
                </x-forms.button>
                @if(request()->anyFilled(['search', 'warehouse_id']))
                    <x-forms.button href="{{ route('admin.stocks.out') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Stock Out Table List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3.5">SKU & Item Name</th>
                        <th class="px-5 py-3.5">Dispatch Warehouse</th>
                        <th class="px-5 py-3.5 text-right">Available Qty</th>
                        <th class="px-5 py-3.5 text-center">Reorder Level</th>
                        <th class="px-5 py-3.5 text-right">Unit Value</th>
                        <th class="px-5 py-3.5 text-center">Stock Status</th>
                        <th class="px-5 py-3.5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($stocks as $stock)
                        @php
                            $computedStatus = $stock->computed_status;
                            $statusBadge = match($computedStatus) {
                                'low_stock' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'out_of_stock' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'overstocked' => 'bg-blue-50 text-blue-700 border-blue-200',
                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-mono text-xs font-bold text-rose-600 mb-0.5">{{ $stock->sku }}</div>
                                <div class="font-bold text-slate-900 text-sm">{{ $stock->product_name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $stock->category }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $stock->warehouse ? $stock->warehouse->name : 'Unassigned' }}</div>
                                <div class="font-mono text-[10px] text-slate-400">Rack: {{ $stock->rack_location ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-extrabold text-sm text-slate-900">{{ number_format($stock->quantity) }} units</div>
                                <div class="text-[10px] text-slate-400">Available: {{ number_format($stock->available_quantity) }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-[11px] text-slate-600 font-mono">
                                    <span>Min: <strong>{{ number_format($stock->min_reorder_level) }}</strong></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-semibold text-slate-900">${{ number_format($stock->unit_cost, 2) }}</div>
                                <div class="text-[11px] font-bold text-slate-500">${{ number_format($stock->total_value, 2) }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 capitalize {{ $statusBadge }}">
                                    {{ str_replace('_', ' ', $computedStatus) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($stock->quantity > 0)
                                    <x-forms.button 
                                        type="button" 
                                        @click="dispatchModalOpen = true; selectedStockId = '{{ $stock->id }}'" 
                                        variant="primary" 
                                        size="sm" 
                                        icon="fas fa-minus"
                                        class="!bg-rose-600 hover:!bg-rose-700"
                                    >
                                        Dispatch Stock Out
                                    </x-forms.button>
                                @else
                                    <span class="px-2.5 py-1 text-xs text-rose-500 font-semibold bg-rose-50 rounded-lg">
                                        Depleted
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fas fa-arrow-up-from-bracket text-3xl text-slate-300 mb-2"></i>
                                <p class="text-sm font-medium text-slate-600">No inventory stock available for dispatch.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock Out Dispatch Modal -->
    <div 
        x-show="dispatchModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="dispatchModalOpen = false" 
            class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 p-6 space-y-5 my-8 relative"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                        <i class="fas fa-arrow-up-from-bracket text-base"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Record Stock Out Dispatch</h3>
                        <p class="text-xs text-slate-500">Dispatch stock for wholesale order</p>
                    </div>
                </div>
                <button type="button" @click="dispatchModalOpen = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.stocks.process-out') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Select Stock Item / Product <span class="text-rose-500">*</span></label>
                    <select name="stock_id" x-model="selectedStockId" required class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                        <option value="">-- Choose Stock Product --</option>
                        @foreach($stocks as $st)
                            <option value="{{ $st->id }}" {{ $st->quantity <= 0 ? 'disabled' : '' }}>
                                {{ $st->sku }} - {{ $st->product_name }} (Available: {{ $st->quantity }} pcs)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Quantity Dispatched (-) <span class="text-rose-500">*</span></label>
                    <input type="number" name="quantity" min="1" value="10" required class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Client / Customer Company Name <span class="text-rose-500">*</span></label>
                    <select name="customer_name" required class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                        <option value="">-- Choose Customer from B2B Clients Table --</option>
                        @if(isset($customers) && count($customers) > 0)
                            @foreach($customers as $c)
                                <option value="{{ $c->company_name ?: $c->name }}">
                                    {{ $c->customer_code }} - {{ $c->company_name ?: $c->name }} (Contact: {{ $c->name }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sales Order / Invoice Ref #</label>
                    <input type="text" name="order_no" placeholder="e.g. ORD-2026-8812" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Dispatch Notes</label>
                    <textarea name="notes" rows="2" placeholder="e.g. Shipped via DHL Express, Bill of Lading #40192" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-rose-600 focus:ring-2 focus:ring-rose-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="dispatchModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs transition-colors">
                        Confirm Stock Out Dispatch
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
