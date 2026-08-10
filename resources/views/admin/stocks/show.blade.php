@extends('admin.layout.app')

@section('title', 'Product Item - ' . $stock->product_name)

@section('content')
<div class="space-y-6 w-full" x-data="{ adjustModalOpen: false }">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-4">
                <img 
                    src="{{ $stock->image_url }}" 
                    alt="{{ $stock->product_name }}" 
                    class="w-16 h-16 object-cover rounded-xl border border-slate-200 shadow-2xs shrink-0" 
                    style="width: 64px; height: 64px; min-width: 64px; min-height: 64px; max-width: 64px; max-height: 64px; object-fit: cover;"
                />
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 bg-indigo-100 text-indigo-800 font-mono font-bold text-xs rounded-md">
                            {{ $stock->sku }}
                        </span>
                        @php
                            $computedStatus = $stock->computed_status;
                            $statusBadge = match($computedStatus) {
                                'low_stock' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'out_of_stock' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'overstocked' => 'bg-blue-50 text-blue-700 border-blue-200',
                                default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            };
                        @endphp
                        <span class="capitalize text-xs font-semibold px-2.5 py-0.5 rounded-full border {{ $statusBadge }}">
                            {{ str_replace('_', ' ', $computedStatus) }}
                        </span>
                    </div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-1">{{ $stock->product_name }}</h1>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button 
                type="button" 
                @click="adjustModalOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-xs transition-colors"
            >
                <i class="fas fa-sliders"></i> Quick Quantity Adjust
            </button>
            <x-forms.button 
                href="{{ route('admin.stocks.edit', $stock) }}" 
                variant="secondary" 
                icon="fas fa-pen-to-square"
            >
                Edit Product
            </x-forms.button>
            <x-forms.button 
                href="{{ route('admin.stocks.index') }}" 
                variant="ghost" 
                icon="fas fa-arrow-left"
            >
                Back
            </x-forms.button>
        </div>
    </div>

    <!-- Overview Stats Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Available Stock Qty</p>
            <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($stock->available_quantity) }} <span class="text-xs font-normal text-slate-500">of {{ number_format($stock->quantity) }} units</span></h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cost Price (Purchase)</p>
            <h3 class="text-2xl font-extrabold text-slate-900">${{ number_format($stock->unit_cost, 2) }}</h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Retail Selling Price</p>
            <h3 class="text-2xl font-extrabold text-indigo-600">${{ number_format($stock->retail_price, 2) }}</h3>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Unit Profit Margin</p>
            <h3 class="text-2xl font-extrabold text-emerald-600">
                +${{ number_format($stock->profit_margin, 2) }}
                <span class="text-xs font-semibold text-emerald-500">({{ $stock->profit_margin_percentage }}%)</span>
            </h3>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Short Desc, Full Desc & Hardware Specs -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Short Description Highlight -->
            @if($stock->short_description)
                <div class="bg-gradient-to-r from-indigo-900 to-slate-900 text-white p-6 rounded-2xl shadow-sm">
                    <div class="text-xs font-bold text-indigo-300 uppercase tracking-wider mb-1.5 flex items-center gap-2">
                        <i class="fas fa-bolt text-indigo-400"></i> Short Overview
                    </div>
                    <p class="text-sm font-medium text-slate-100 leading-relaxed">{{ $stock->short_description }}</p>
                </div>
            @endif

            <!-- Dynamic Hardware & Technical Specifications Sheet -->
            <x-forms.card title="Technical & Product Specifications" subtitle="Detailed hardware parameters and dynamic product attributes">
                @if(!empty($stock->details) && is_array($stock->details) && count($stock->details) > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($stock->details as $specKey => $specVal)
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/60 hover:border-indigo-200 transition-colors">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                                    {{ str_replace('_', ' ', ucwords($specKey)) }}
                                </span>
                                <div class="font-semibold text-slate-900 text-xs mt-1">
                                    {{ $specVal }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-xs text-slate-400 italic py-2">No detailed technical specifications recorded for this item.</div>
                @endif
            </x-forms.card>

            <!-- Multi-Tier Volume Wholesale Pricing Table -->
            <x-forms.card title="Multi-Tier Volume Pricing (Bulk Quantity Breaks)" subtitle="Wholesale unit pricing breakdown based on order volume">
                @if(!empty($stock->tier_prices) && is_array($stock->tier_prices) && count($stock->tier_prices) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-[11px] font-bold text-slate-400 uppercase border-b border-slate-100">
                                    <th class="px-4 py-2.5">Price Tier</th>
                                    <th class="px-4 py-2.5">Order Quantity Range</th>
                                    <th class="px-4 py-2.5 text-right">Unit Price ($ USD)</th>
                                    <th class="px-4 py-2.5 text-right">Per Unit Savings</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs">
                                @foreach($stock->tier_prices as $index => $tier)
                                    @php
                                        $min = $tier['min_qty'] ?? 1;
                                        $max = isset($tier['max_qty']) && $tier['max_qty'] !== null && $tier['max_qty'] !== '' ? $tier['max_qty'] : null;
                                        $tierPrice = (float) ($tier['price'] ?? $stock->retail_price);
                                        $savings = $stock->retail_price > $tierPrice ? ($stock->retail_price - $tierPrice) : 0;
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-4 py-3 font-bold text-indigo-600">
                                            Tier {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-slate-800">
                                            @if($max)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-md font-mono">
                                                    {{ $min }} – {{ $max }} units
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-md font-mono">
                                                    {{ $min }}+ units (Bulk Tier)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right font-extrabold text-slate-900 text-sm">
                                            ${{ number_format($tierPrice, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                            @if($savings > 0)
                                                Save ${{ number_format($savings, 2) }} / unit
                                            @else
                                                <span class="text-slate-400 font-normal">Base Price</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-500 flex items-center justify-between">
                        <div>Standard single-tier pricing active. Standard retail price: <strong class="text-indigo-600">${{ number_format($stock->retail_price, 2) }}</strong></div>
                        <a href="{{ route('admin.stocks.edit', $stock) }}" class="text-indigo-600 hover:underline font-semibold">Configure Price Tiers &rarr;</a>
                    </div>
                @endif
            </x-forms.card>

            <!-- Full Product Description -->
            <x-forms.card title="Full Product Description" subtitle="Comprehensive feature descriptions and compatibility details">
                <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $stock->description ?? 'No full description provided for this product item.' }}
                </div>
            </x-forms.card>

            <!-- Location & Warehouse Parameters -->
            <x-forms.card title="Inventory Location & Shelf Details" subtitle="Storage warehouse, rack location, and reorder levels">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <div>
                        <span class="text-slate-400 block font-medium">Product Category:</span>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $stock->category }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Assigned Warehouse Hub:</span>
                        @if($stock->warehouse)
                            <a href="{{ route('admin.warehouses.show', $stock->warehouse) }}" class="font-bold text-indigo-600 hover:text-indigo-800 text-sm mt-0.5 block">
                                {{ $stock->warehouse->name }} ({{ $stock->warehouse->code }})
                            </a>
                        @else
                            <p class="font-bold text-slate-400 text-sm mt-0.5">Unassigned</p>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Rack / Bin Location:</span>
                        <p class="font-mono font-bold text-slate-900 text-sm mt-0.5">{{ $stock->rack_location ?? 'Not Specified' }}</p>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-medium">Safety Stock Threshold (Min):</span>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ number_format($stock->min_reorder_level) }} units</p>
                    </div>
                </div>
            </x-forms.card>

        </div>

        <!-- Right Column: Audit Logs & Notes -->
        <div class="space-y-6">
            <x-forms.card title="Inventory Notes & Logs" subtitle="System records and adjustment history">
                <div class="text-xs text-slate-600 space-y-3 whitespace-pre-line leading-relaxed">
                    {{ $stock->notes ?? 'No operational notes or adjustment logs recorded for this SKU line item.' }}
                </div>
            </x-forms.card>
        </div>

    </div>

    <!-- Quick Stock Quantity Adjustment Modal -->
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
                        <p class="text-xs text-slate-500">{{ $stock->product_name }} ({{ $stock->sku }})</p>
                    </div>
                </div>
                <button @click="adjustModalOpen = false" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.stocks.adjust', $stock) }}" method="POST" class="space-y-4">
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
                    <input type="text" name="adjustment_reason" placeholder="e.g. Received shipment / Stock count audit" class="w-full text-xs rounded-xl border-slate-200 py-2.5" />
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
        </div>
    </div>

</div>
@endsection
