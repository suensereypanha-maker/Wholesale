@extends('admin.layout.app')

@section('title', 'Stock Adjustments Management')

@section('content')
<div class="space-y-6 w-full" x-data="{ adjustModalOpen: false, historyModalOpen: false, activeStock: null }">

    <!-- Page Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-3 bg-amber-50 text-amber-600 rounded-xl border border-amber-100/80 shadow-xs">
                <i class="fas fa-sliders text-xl"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Stock Level Controls & Adjustments</h1>
                <p class="text-xs text-slate-500">Reconcile physical inventory, adjust stock counts with audit reasons, and review historical inventory changes.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <x-forms.button 
                href="{{ route('admin.stocks.in') }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-arrow-down-to-bracket"
            >
                Stock In
            </x-forms.button>

            <x-forms.button 
                href="{{ route('admin.stocks.out') }}" 
                variant="secondary" 
                size="sm" 
                icon="fas fa-arrow-up-from-bracket"
            >
                Stock Out
            </x-forms.button>

            <x-forms.button 
                href="{{ route('admin.stocks.index') }}" 
                variant="ghost" 
                size="sm" 
                icon="fas fa-box-archive"
            >
                Products Catalog
            </x-forms.button>
        </div>
    </div>

    <!-- Inventory Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-barcode text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total SKUs</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalItems) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">On-Hand Stock Qty</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalQuantity) }} <span class="text-xs font-normal text-slate-500">units</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold {{ $lowStockCount > 0 ? 'animate-pulse' : '' }}">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Requires Adjustment</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-500">low/out items</span></h3>
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
        <form action="{{ route('admin.stocks.adjustments') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
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
                        <option value="">All Stock Statuses</option>
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
                    <x-forms.button href="{{ route('admin.stocks.adjustments') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Stock Adjustments Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3.5">SKU & Product Name</th>
                        <th class="px-5 py-3.5">Warehouse Location</th>
                        <th class="px-5 py-3.5">Rack / Bin</th>
                        <th class="px-5 py-3.5 text-right">Current Stock Qty</th>
                        <th class="px-5 py-3.5 text-center">Thresholds</th>
                        <th class="px-5 py-3.5">Latest Adjustment Log</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Adjustment Action</th>
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
                            
                            // Extract last adjustment note line
                            $notesLines = array_filter(explode("\n", (string)$stock->notes));
                            $lastNote = !empty($notesLines) ? end($notesLines) : 'Initial stock record';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Product Details -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $stock->image_url }}" 
                                        alt="{{ $stock->product_name }}" 
                                        class="w-10 h-10 object-cover rounded-lg border border-slate-200 shrink-0 shadow-2xs" 
                                        style="width: 40px; height: 40px; min-width: 40px; min-height: 40px; object-fit: cover;"
                                    />
                                    <div>
                                        <div class="flex items-center gap-2 mb-0.5">
                                            <span class="font-mono text-xs font-bold text-indigo-600">{{ $stock->sku }}</span>
                                            <span class="text-[10px] font-medium text-slate-400">({{ $stock->category }})</span>
                                        </div>
                                        <div class="font-bold text-slate-900 text-sm">
                                            {{ $stock->product_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Warehouse -->
                            <td class="px-5 py-4">
                                @if($stock->warehouse)
                                    <div class="font-semibold text-slate-800">{{ $stock->warehouse->name }}</div>
                                    <div class="font-mono text-[10px] text-slate-400">{{ $stock->warehouse->code }}</div>
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

                            <!-- Reorder Thresholds -->
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 rounded-lg text-[10px] text-slate-600 font-mono">
                                    <span>Min: <strong>{{ $stock->min_reorder_level }}</strong></span>
                                    <span>/</span>
                                    <span>Max: <strong>{{ $stock->max_capacity }}</strong></span>
                                </div>
                            </td>

                            <!-- Latest Adjustment Note -->
                            <td class="px-5 py-4 max-w-xs">
                                <div class="text-[11px] text-slate-600 line-clamp-2 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    <i class="fas fa-clock-rotate-left text-[10px] text-slate-400 mr-1"></i>
                                    {{ $lastNote }}
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border inline-flex items-center gap-1.5 capitalize {{ $statusBadge }}">
                                    @if($computedStatus === 'low_stock')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    @elseif($computedStatus === 'out_of_stock')
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                    @endif
                                    {{ str_replace('_', ' ', $computedStatus) }}
                                </span>
                            </td>

                            <!-- Adjustment Actions (No product edit/delete buttons) -->
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if(auth()->user()?->canDo(['products.edit', 'inventory.edit', 'manage_products']))
                                        <button 
                                            type="button" 
                                            @click="adjustModalOpen = true; activeStock = {{ json_encode($stock) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors"
                                        >
                                            <i class="fas fa-sliders text-xs"></i>
                                            <span>Adjust Stock</span>
                                        </button>
                                    @endif

                                    <button 
                                        type="button" 
                                        @click="historyModalOpen = true; activeStock = {{ json_encode($stock) }}"
                                        class="p-1.5 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors"
                                        title="View Adjustment Audit History Log"
                                    >
                                        <i class="fas fa-history text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400 space-y-3">
                                <i class="fas fa-sliders text-3xl text-slate-300"></i>
                                <p class="text-sm font-medium text-slate-600">No stock records found matching your filters.</p>
                                <p class="text-xs text-slate-400">Try clearing search keywords or selecting all warehouses.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Stock Adjustment Modal -->
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
                    <span class="p-2.5 bg-amber-50 text-amber-600 rounded-xl border border-amber-100">
                        <i class="fas fa-sliders text-base"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Adjust Inventory Stock Qty</h3>
                        <p class="text-xs text-slate-500" x-text="activeStock ? activeStock.product_name + ' (' + activeStock.sku + ')' : ''"></p>
                    </div>
                </div>
                <button @click="adjustModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <template x-if="activeStock">
                <form :action="'{{ url('admin/stocks') }}/' + activeStock.id + '/adjust'" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500 font-medium">Current Stock Level:</span>
                        <span class="text-sm font-extrabold text-slate-900" x-text="activeStock.quantity + ' units'"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Adjustment Type</label>
                        <select name="adjustment_type" class="w-full text-xs rounded-xl border-slate-200 py-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="add">Add Stock (+) (Restock / Received Intake)</option>
                            <option value="subtract">Deduct Stock (-) (Damaged / Defective / Write-off)</option>
                            <option value="set">Set Exact Quantity (=) (Physical Inventory Audit Count)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Quantity Amount</label>
                        <input type="number" name="adjustment_amount" min="1" value="10" required class="w-full text-xs rounded-xl border-slate-200 py-2.5 focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Adjustment Reason & Audit Notes</label>
                        <input type="text" name="adjustment_reason" placeholder="e.g. Q3 Physical Warehouse Count / Damaged during transit" class="w-full text-xs rounded-xl border-slate-200 py-2.5 focus:border-indigo-500 focus:ring-indigo-500" required />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                        <button type="button" @click="adjustModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition-colors">
                            Apply Adjustment
                        </button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    <!-- History Audit Log Modal -->
    <div 
        x-show="historyModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
    >
        <div 
            @click.away="historyModalOpen = false" 
            class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 p-6 space-y-5"
        >
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100">
                        <i class="fas fa-history text-base"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Adjustment Audit Log</h3>
                        <p class="text-xs text-slate-500" x-text="activeStock ? activeStock.product_name + ' (' + activeStock.sku + ')' : ''"></p>
                    </div>
                </div>
                <button @click="historyModalOpen = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <template x-if="activeStock">
                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    <template x-if="activeStock.notes">
                        <div class="space-y-2">
                            <template x-for="(line, idx) in activeStock.notes.split('\n')" :key="idx">
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-700 flex items-start gap-2.5">
                                    <i class="fas fa-circle-dot text-indigo-500 mt-0.5 text-[10px]"></i>
                                    <span x-text="line"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!activeStock.notes">
                        <p class="text-xs text-slate-400 italic text-center py-6">No historical adjustment logs recorded yet.</p>
                    </template>
                </div>
            </template>

            <div class="flex justify-end pt-3 border-t border-slate-100">
                <button type="button" @click="historyModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
