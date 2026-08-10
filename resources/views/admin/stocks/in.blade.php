@extends('admin.layout.app')

@section('title', 'Stock In - Inventory Intake')

@section('content')
<div class="space-y-6 w-full" x-data="{ intakeModalOpen: false, intakeMode: 'existing', selectedStockId: '' }">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <i class="fas fa-arrow-down-to-bracket text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Stock In (Inventory Restock & Receiving)</h1>
                    <p class="text-xs text-slate-500">Record supplier shipments, inventory intake, purchase orders, or register new stock items</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-forms.button 
                type="button"
                @click="intakeModalOpen = true; intakeMode = 'existing'" 
                variant="primary" 
                icon="fas fa-boxes-stacked"
                class="!bg-indigo-600 hover:!bg-indigo-700"
            >
                Stock In Existing Item
            </x-forms.button>
            <x-forms.button 
                type="button"
                @click="intakeModalOpen = true; intakeMode = 'new'" 
                variant="secondary" 
                icon="fas fa-plus-circle"
            >
                + Intake New Item
            </x-forms.button>
        </div>
    </div>

    <!-- Overview Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-boxes-packing text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total In-Stock Units</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalIntakeUnits) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-list-check text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active SKUs</p>
                <h3 class="text-xl font-bold text-slate-900">{{ number_format($totalItemsCount) }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-warehouse text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Receiving Facilities</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $warehouseCount }} <span class="text-xs font-normal text-slate-500">active</span></h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold">
                <i class="fas fa-sack-dollar text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Inflow Valuation</p>
                <h3 class="text-xl font-bold text-slate-900">${{ number_format($totalValuation, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Search & Filter Toolbar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form action="{{ route('admin.stocks.in') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
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
                    <select name="warehouse_id" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                        <option value="">All Receiving Warehouses</option>
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
                    Filter Stock In
                </x-forms.button>
                @if(request()->anyFilled(['search', 'warehouse_id']))
                    <x-forms.button href="{{ route('admin.stocks.in') }}" variant="ghost" size="sm" icon="fas fa-rotate-left">
                        Reset
                    </x-forms.button>
                @endif
            </div>
        </form>
    </div>

    <!-- Stock In Table List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-5 py-3.5">SKU & Item Name</th>
                        <th class="px-5 py-3.5">Warehouse Location</th>
                        <th class="px-5 py-3.5 text-right">Current Available Stock</th>
                        <th class="px-5 py-3.5 text-center">Capacity Utilization</th>
                        <th class="px-5 py-3.5 text-right">Unit Value</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($stocks as $stock)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <div class="font-mono text-xs font-bold text-indigo-600 mb-0.5">{{ $stock->sku }}</div>
                                <div class="font-bold text-slate-900 text-sm">{{ $stock->product_name }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $stock->category }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $stock->warehouse ? $stock->warehouse->name : 'Unassigned' }}</div>
                                <div class="font-mono text-[10px] text-slate-400">Rack: {{ $stock->rack_location ?? 'N/A' }}</div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-extrabold text-sm text-emerald-600">+{{ number_format($stock->quantity) }} units</div>
                                <div class="text-[10px] text-slate-400">Reserved: {{ $stock->reserved_quantity }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-lg text-[11px] text-slate-600 font-mono">
                                    <span>Cap: <strong>{{ number_format($stock->max_capacity) }}</strong></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-semibold text-slate-900">${{ number_format($stock->unit_cost, 2) }}</div>
                                <div class="text-[11px] font-bold text-emerald-600">${{ number_format($stock->total_value, 2) }}</div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200 capitalize">
                                    In Stock
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <x-forms.button 
                                    type="button" 
                                    @click="intakeModalOpen = true; intakeMode = 'existing'; selectedStockId = '{{ $stock->id }}'" 
                                    variant="primary" 
                                    size="sm" 
                                    icon="fas fa-plus"
                                    class="!bg-indigo-600 hover:!bg-indigo-700"
                                >
                                    Receive Stock In
                                </x-forms.button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fas fa-arrow-down-to-bracket text-3xl text-slate-300 mb-2"></i>
                                <p class="text-sm font-medium text-slate-600">No stock items available for intake.</p>
                                <div class="mt-3">
                                    <x-forms.button 
                                        type="button" 
                                        @click="intakeModalOpen = true; intakeMode = 'new'" 
                                        variant="primary" 
                                        size="sm" 
                                        icon="fas fa-plus"
                                    >
                                        Intake New Stock Item
                                    </x-forms.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stock In Intake Modal (Dual Mode: Existing Select vs New Input) -->
    <div 
        x-show="intakeModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            @click.away="intakeModalOpen = false" 
            class="bg-white w-full max-w-xl rounded-2xl shadow-2xl border border-slate-200 p-6 space-y-5 my-8 relative"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                        <i class="fas fa-arrow-down-to-bracket text-lg"></i>
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Record Stock In Intake</h3>
                        <p class="text-xs text-slate-500">Select existing item or register a new product intake</p>
                    </div>
                </div>
                <button type="button" @click="intakeModalOpen = false" class="text-slate-400 hover:text-slate-600 p-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Mode Switcher Tabs -->
            <div class="grid grid-cols-2 p-1.5 bg-slate-100 rounded-xl text-xs font-semibold gap-1.5 border border-slate-200/60">
                <button 
                    type="button" 
                    @click="intakeMode = 'existing'" 
                    :class="intakeMode === 'existing' ? 'bg-white text-indigo-600 shadow-xs font-bold border border-slate-200' : 'text-slate-600 hover:text-slate-900'" 
                    class="py-2.5 px-3 rounded-lg transition-all text-center flex items-center justify-center gap-2"
                >
                    <i class="fas fa-boxes-stacked text-xs"></i> Select Existing Item
                </button>
                <button 
                    type="button" 
                    @click="intakeMode = 'new'" 
                    :class="intakeMode === 'new' ? 'bg-white text-indigo-600 shadow-xs font-bold border border-slate-200' : 'text-slate-600 hover:text-slate-900'" 
                    class="py-2.5 px-3 rounded-lg transition-all text-center flex items-center justify-center gap-2"
                >
                    <i class="fas fa-plus-circle text-xs"></i> Input New Item
                </button>
            </div>

            <!-- Intake Form -->
            <form action="{{ route('admin.stocks.process-in') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="intake_mode" :value="intakeMode" />

                <!-- MODE 1: EXISTING ITEM -->
                <div x-show="intakeMode === 'existing'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Select Stock Item / Product <span class="text-rose-500">*</span></label>
                        <select name="stock_id" x-model="selectedStockId" :disabled="intakeMode !== 'existing'" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                            <option value="">-- Choose Ready Stock Product --</option>
                            @foreach($stocks as $st)
                                <option value="{{ $st->id }}">{{ $st->sku }} - {{ $st->product_name }} (Current: {{ $st->quantity }} pcs)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Quantity Received (+) <span class="text-rose-500">*</span></label>
                        <input type="number" name="quantity" min="1" value="50" :disabled="intakeMode !== 'existing'" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" />
                    </div>
                </div>

                <!-- MODE 2: NEW ITEM -->
                <div x-show="intakeMode === 'new'" class="space-y-4" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">SKU Code <span class="text-rose-500">*</span></label>
                            <input 
                                type="text" 
                                name="sku" 
                                placeholder="e.g. SKU-CPU-88" 
                                :disabled="intakeMode !== 'new'" 
                                class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-mono uppercase font-bold text-indigo-700" 
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Product Category <span class="text-rose-500">*</span></label>
                            <select name="category" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                    @endforeach
                                @else
                                    <option value="CPUs & Processors">CPUs & Processors</option>
                                    <option value="RAM & Memory Modules">RAM & Memory Modules</option>
                                    <option value="SSDs & Storage Drives">SSDs & Storage Drives</option>
                                    <option value="Graphics Cards & GPUs">Graphics Cards & GPUs</option>
                                    <option value="Laptop Parts & Components">Laptop Parts & Components</option>
                                    <option value="Electronics & Energy">Electronics & Energy</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Product Name <span class="text-rose-500">*</span></label>
                        <input 
                            type="text" 
                            name="product_name" 
                            placeholder="e.g. Intel Core i7 13700K 16-Core Processor" 
                            :disabled="intakeMode !== 'new'" 
                            class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" 
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Short Description</label>
                        <input 
                            type="text" 
                            name="short_description" 
                            placeholder="Brief product summary e.g. 16-core flagship CPU 5.4GHz Boost" 
                            :disabled="intakeMode !== 'new'" 
                            class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" 
                        />
                    </div>

                    <!-- CPU / Specs Inputs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Processor Brand</label>
                            <input type="text" name="cpu_brand" placeholder="e.g. Intel / AMD" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 font-medium text-slate-800" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">CPU Model</label>
                            <input type="text" name="cpu_model" placeholder="e.g. Core i7 13700K" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 font-medium text-slate-800" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div class="sm:col-span-1">
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Warehouse <span class="text-rose-500">*</span></label>
                            <select name="warehouse_id" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2 px-2 font-medium text-slate-800">
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Qty (+) <span class="text-rose-500">*</span></label>
                            <input type="number" name="quantity" min="1" value="100" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 font-medium text-slate-800" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Cost ($) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="unit_cost" min="0" value="280.00" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 font-medium text-slate-800" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Retail ($) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" name="retail_price" min="0" value="350.00" :disabled="intakeMode !== 'new'" class="w-full text-xs rounded-xl border border-slate-300 py-2 px-3 font-medium text-slate-800" />
                        </div>
                    </div>
                </div>

                <!-- COMMON FIELDS FOR BOTH MODES -->
                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier / Vendor Name</label>
                            <select name="supplier_name" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800">
                                <option value="">-- Select Supplier / Vendor --</option>
                                @if(isset($suppliers) && count($suppliers) > 0)
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->company_name ?: $sup->name }}">{{ $sup->company_name ?: $sup->name }} ({{ $sup->code }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">PO / Delivery Ref #</label>
                            <input type="text" name="reference_no" placeholder="e.g. PO-2026-9041" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Intake Notes</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Verified batch #8014, inspected quality upon dock arrival" class="w-full text-xs rounded-xl border border-slate-300 bg-white hover:border-slate-400 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 shadow-2xs py-2.5 px-3 font-medium text-slate-800"></textarea>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="intakeModalOpen = false" class="px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition-colors flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        <span x-text="intakeMode === 'new' ? 'Register & Confirm Stock In' : 'Confirm Stock In Intake'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
