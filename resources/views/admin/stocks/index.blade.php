@extends('admin.layout.app')

@section('title', 'Stock & Inventory Management')

@section('content')
<div class="space-y-6 w-full" x-data="{ adjustModalOpen: false, activeStock: null }">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                    <i class="fas fa-boxes-stacked text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Stock & Inventory Level Control</h1>
                    <p class="text-xs text-slate-500">Monitor multi-warehouse inventory levels, reorder alerts, stock valuations, and rack allocations</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()?->canDo(['products.create', 'inventory.create', 'manage_products']))
                <x-forms.button 
                    href="{{ route('admin.stocks.create') }}" 
                    variant="primary" 
                    icon="fas fa-plus"
                    class="!bg-emerald-600 hover:!bg-emerald-700"
                >
                    Add Inventory Item
                </x-forms.button>
            @endif
        </div>
    </div>

    <!-- Overview Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-barcode text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SKU Line Items</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalItems) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-packing text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Quantity</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalQuantity) }} <span class="text-xs font-normal text-slate-500">units</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold {{ $lowStockCount > 0 ? 'animate-pulse' : '' }}">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Reorder / Low Stock</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-500">items</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-sack-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Inventory Valuation</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($totalValuation, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filters Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.stocks.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <div class="w-full sm:w-64">
                    <x-forms.input 
                        name="search" 
                        placeholder="Search product, SKU, rack..." 
                        :value="request('search')"
                        icon="fas fa-search"
                    />
                </div>

                <div class="w-full sm:w-48">
                    <select name="warehouse_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->code }} - {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-40">
                    <select name="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 py-2.5">
                        <option value="">All Statuses</option>
                        <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Low Stock Alert</option>
                        <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="overstocked" {{ request('status') == 'overstocked' ? 'selected' : '' }}>Overstocked</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                <x-forms.button type="submit" variant="secondary" size="sm" icon="fas fa-filter">
                    Filter Stock
                </x-forms.button>
                @if(request()->anyFilled(['search', 'warehouse_id', 'status']))
                    <x-forms.button href="{{ route('admin.stocks.index') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Inventory Stock Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3.5">SKU & Product Name</th>
                        <th class="px-5 py-3.5">Warehouse Location</th>
                        <th class="px-5 py-3.5">Rack / Bin</th>
                        <th class="px-5 py-3.5 text-right">Qty (Available)</th>
                        <th class="px-5 py-3.5 text-center">Reorder Threshold</th>
                        <th class="px-5 py-3.5 text-right">Unit Cost / Total</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Actions</th>
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
                            <!-- SKU & Product Name & Specs -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $stock->image_url }}" 
                                        alt="{{ $stock->product_name }}" 
                                        class="w-12 h-12 object-cover rounded-lg border border-slate-200 shrink-0 shadow-2xs" 
                                        style="width: 48px; height: 48px; min-width: 48px; min-height: 48px; max-width: 48px; max-height: 48px; object-fit: cover;"
                                    />
                                    <div>
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span class="font-mono text-xs font-bold text-indigo-600">{{ $stock->sku }}</span>
                                            @if(!empty($stock->details['cpu_brand']) || !empty($stock->details['cpu_model']))
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-sky-50 text-sky-700 text-[10px] font-bold rounded border border-sky-200">
                                                    <i class="fas fa-microchip text-[9px]"></i>
                                                    {{ $stock->details['cpu_brand'] ?? '' }} {{ $stock->details['cpu_model'] ?? '' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-900 text-sm hover:text-indigo-600">
                                            <a href="{{ route('admin.stocks.show', $stock) }}">{{ $stock->product_name }}</a>
                                        </div>
                                        @if($stock->short_description)
                                            <div class="text-[11px] text-slate-500 line-clamp-1 max-w-xs mt-0.5">{{ $stock->short_description }}</div>
                                        @endif
                                        <div class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $stock->category }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Warehouse -->
                            <td class="px-5 py-4">
                                @if($stock->warehouse)
                                    <a href="{{ route('admin.warehouses.show', $stock->warehouse) }}" class="group">
                                        <div class="font-semibold text-slate-800 group-hover:text-indigo-600">{{ $stock->warehouse->name }}</div>
                                        <div class="font-mono text-[10px] text-slate-400">{{ $stock->warehouse->code }}</div>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>

                            <!-- Rack Location -->
                            <td class="px-5 py-4 font-mono font-medium text-slate-700">
                                {{ $stock->rack_location ?? '-' }}
                            </td>

                            <!-- Qty & Available -->
                            <td class="px-5 py-4 text-right">
                                <div class="font-extrabold text-sm text-slate-900">{{ number_format($stock->quantity) }} units</div>
                                <div class="text-[10px] text-slate-500">
                                    Available: <strong class="text-emerald-600">{{ number_format($stock->available_quantity) }}</strong> 
                                    @if($stock->reserved_quantity > 0)
                                        | Reserved: <span class="text-amber-600">{{ $stock->reserved_quantity }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Reorder Threshold -->
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-[11px] text-slate-600 font-mono">
                                    <span>Min: <strong>{{ $stock->min_reorder_level }}</strong></span>
                                    <span>/</span>
                                    <span>Max: <strong>{{ $stock->max_capacity }}</strong></span>
                                </div>
                            </td>

                            <!-- Unit Cost / Retail Price / Volume Tiers -->
                            <td class="px-5 py-4 text-right">
                                <div class="text-xs text-slate-600">
                                    Cost: <strong class="text-slate-900">${{ number_format($stock->unit_cost, 2) }}</strong> | Retail: <strong class="text-indigo-700">${{ number_format($stock->retail_price, 2) }}</strong>
                                </div>
                                @if(!empty($stock->tier_prices) && is_array($stock->tier_prices))
                                    <div class="flex items-center justify-end gap-1 flex-wrap mt-1">
                                        @foreach(array_slice($stock->tier_prices, 0, 3) as $t)
                                            @php
                                                $tMin = $t['min_qty'] ?? 1;
                                                $tMax = isset($t['max_qty']) && $t['max_qty'] !== null && $t['max_qty'] !== '' ? $t['max_qty'] : '+';
                                                $tP = (float)($t['price'] ?? 0);
                                            @endphp
                                            <span class="inline-flex items-center px-1.5 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-mono font-bold rounded border border-indigo-200/60" title="Tier: {{ $tMin }}-{{ $tMax }} qty = ${{ number_format($tP, 2) }}">
                                                {{ $tMin }}-{{ $tMax }}: ${{ number_format($tP, 0) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-[10px] font-bold text-emerald-600 mt-0.5">
                                        Margin: +${{ number_format($stock->profit_margin, 2) }} ({{ $stock->profit_margin_percentage }}%)
                                    </div>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border inline-flex items-center gap-1.5 capitalize {{ $statusBadge }}">
                                    @if($computedStatus === 'low_stock')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    @elseif($computedStatus === 'out_of_stock')
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    @endif
                                    {{ str_replace('_', ' ', $computedStatus) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if(auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products']))
                                        <!-- Quick Adjust Button -->
                                        <button 
                                            type="button" 
                                            @click="adjustModalOpen = true; activeStock = {{ json_encode($stock) }}"
                                            class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                                            title="Quick Stock Adjustment"
                                        >
                                            <i class="fas fa-sliders text-xs"></i>
                                        </button>
                                    @endif

                                    <!-- View Details -->
                                    <x-forms.button 
                                        href="{{ route('admin.stocks.show', $stock) }}" 
                                        variant="ghost" 
                                        size="sm" 
                                        icon="fas fa-eye"
                                        title="View Stock Details" 
                                    />

                                    @if(auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products']))
                                        <!-- Edit Stock -->
                                        <x-forms.button 
                                            href="{{ route('admin.stocks.edit', $stock) }}" 
                                            variant="ghost" 
                                            size="sm" 
                                            icon="fas fa-pen-to-square"
                                            title="Edit Stock Item" 
                                        />
                                    @endif

                                    @if(auth()->user()?->canDo(['products.delete', 'inventory.delete', 'manage_products']))
                                        <!-- Delete Stock -->
                                        <x-forms.form 
                                            action="{{ route('admin.stocks.destroy', $stock) }}" 
                                            method="DELETE" 
                                            class="inline-block !space-y-0"
                                            onsubmit="return confirm('Are you sure you want to delete stock item {{ $stock->product_name }}?');"
                                        >
                                            <x-forms.button 
                                                type="submit" 
                                                variant="ghost" 
                                                size="sm" 
                                                icon="fas fa-trash-can" 
                                                class="text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                                                title="Delete Stock Item"
                                            />
                                        </x-forms.form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-boxes-stacked text-3xl text-slate-300"></i>
                                <p class="text-sm font-medium text-slate-600">No inventory stock items found.</p>
                                <p class="text-xs text-slate-400">Try clearing your search filters or click below to register new stock.</p>
                                <div class="pt-2">
                                    <x-forms.button href="{{ route('admin.stocks.create') }}" variant="primary" icon="fas fa-plus">
                                        Add Inventory Stock Item
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stock Quantity Adjustment Modal (Alpine.js) -->
    <div 
        x-show="adjustModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
        <div 
            @click.away="adjustModalOpen = false" 
            class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 p-6 space-y-5"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <i class="fas fa-sliders text-base"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Adjust Stock Quantity</h3>
                        <p class="text-xs text-slate-500" x-text="activeStock ? activeStock.product_name + ' (' + activeStock.sku + ')' : ''"></p>
                    </div>
                </div>
                <button @click="adjustModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <template x-if="activeStock">
                <form :action="'{{ url('admin/stocks') }}/' + activeStock.id + '/adjust'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Adjustment Action</label>
                        <select name="adjustment_type" class="w-full text-xs rounded-xl border-slate-200 py-2.5">
                            <option value="add">Add Stock (+) (Restock / Received Shipment)</option>
                            <option value="subtract">Deduct Stock (-) (Damaged / Return / Written off)</option>
                            <option value="set">Set Exact Quantity (=) (Inventory Audit)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Quantity Amount</label>
                        <input type="number" name="adjustment_amount" min="1" value="10" required class="w-full text-xs rounded-xl border-slate-200 py-2.5" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Reason / Note</label>
                        <input type="text" name="adjustment_reason" placeholder="e.g. Monthly Audit Count / Damage write-off" class="w-full text-xs rounded-xl border-slate-200 py-2.5" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="adjustModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs">
                            Apply Adjustment
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

</div>
@endsection
