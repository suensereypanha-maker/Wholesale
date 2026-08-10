@extends('admin.layout.app')

@section('title', 'Add New Product & Stock Item')

@section('content')
<div class="w-full space-y-6">

    <!-- Page Header & Back Action -->
    <div class="flex items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-box-archive text-xl"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Add New Product & Stock</h1>
                <p class="text-xs text-slate-500">Register product information, CPU hardware specs, pricing structure, and inventory stock quantity</p>
            </div>
        </div>
        <x-forms.button href="{{ route('admin.stocks.index') }}" variant="secondary" icon="fas fa-arrow-left">
            Back to Catalog
        </x-forms.button>
    </div>

    <!-- Create Form Card -->
    <x-forms.card title="Product & Inventory Details" subtitle="Provide short description, full description, CPU/hardware specs, cost price, retail price, product image, and stock levels">
        <x-forms.form action="{{ route('admin.stocks.store') }}" method="POST" hasFiles>
            
            <div class="space-y-6">
                <!-- Core Product Information Section -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-indigo-500"></i> Basic Product Overview & Image
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Warehouse -->
                        <x-forms.select 
                            name="warehouse_id" 
                            label="Assigned Warehouse Hub" 
                            required 
                        >
                            <option value="">Select Target Warehouse</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id', request('warehouse_id')) == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ $wh->code }})
                                </option>
                            @endforeach
                        </x-forms.select>

                        <!-- SKU Code -->
                        <x-forms.input 
                            name="sku" 
                            label="Product SKU Code" 
                            placeholder="e.g. SKU-CPU-INTEL-I7" 
                            :value="old('sku')" 
                            required 
                        />

                        <!-- Product Name -->
                        <x-forms.input 
                            name="product_name" 
                            label="Product / Item Name" 
                            placeholder="e.g. Intel Core i7 13700K 16-Core Processor" 
                            :value="old('product_name')" 
                            required 
                        />

                        <!-- Category -->
                        <x-forms.select 
                            name="category" 
                            label="Product Category" 
                            required 
                        >
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>

                    <!-- Product Image & Short Description Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <x-forms.file 
                            name="image" 
                            label="Product Image" 
                            helpText="Upload product image (PNG, JPG, WEBP up to 4MB)" 
                        />

                        <x-forms.input 
                            name="short_description" 
                            label="Short Description" 
                            placeholder="e.g. High performance 16-core CPU with boost speeds up to 5.4GHz." 
                            :value="old('short_description')" 
                            helper="Brief product summary displayed in catalog search and quick view"
                        />
                    </div>

                    <!-- Full Description -->
                    <div class="mt-4">
                        <x-forms.textarea 
                            name="description" 
                            label="Full Product Description" 
                            placeholder="Detailed product specifications, warranty info, usage instructions, and compatibility notes..." 
                            rows="4" 
                        >{{ old('description') }}</x-forms.textarea>
                    </div>
                </div>

                <hr class="border-slate-100" />

                <!-- Dynamic Hardware & Technical Specifications Section -->
                <div x-data="{
                    specs: [
                        { key: 'Processor Family', value: 'Intel Core i7' },
                        { key: 'CPU Model', value: '13700K' },
                        { key: 'Cores / Threads', value: '16 Cores (8P + 8E) / 24 Threads' },
                        { key: 'Clock Frequency', value: '3.40 GHz Base (5.40 GHz Turbo)' },
                        { key: 'L2/L3 Cache', value: '30MB Smart Cache' },
                        { key: 'RAM / Memory', value: '32GB DDR5 5600MHz' },
                        { key: 'Storage Drive', value: '1TB NVMe M.2 SSD' }
                    ],
                    addSpec(k = '', v = '') {
                        this.specs.push({ key: k, value: v });
                    },
                    removeSpec(i) {
                        this.specs.splice(i, 1);
                    },
                    loadPreset(type) {
                        if (type === 'cpu') {
                            this.specs = [
                                { key: 'Processor Family', value: 'Intel Core i7' },
                                { key: 'CPU Model', value: 'Core i7-13700K' },
                                { key: 'Generation', value: '13th Gen Raptor Lake' },
                                { key: 'Physical Cores', value: '16 Cores (8P + 8E)' },
                                { key: 'Total Threads', value: '24 Threads' },
                                { key: 'Base Clock', value: '3.40 GHz' },
                                { key: 'Turbo Boost', value: '5.40 GHz Max Boost' },
                                { key: 'Smart Cache', value: '30MB Cache' },
                                { key: 'Socket', value: 'LGA 1700' }
                            ];
                        } else if (type === 'ram') {
                            this.specs = [
                                { key: 'Memory Capacity', value: '32GB (2 x 16GB)' },
                                { key: 'Memory Type', value: 'DDR5 SDRAM' },
                                { key: 'Speed Frequency', value: '6000MHz (PC5-48000)' },
                                { key: 'Form Factor', value: '288-Pin DIMM' },
                                { key: 'Tested Latency', value: 'CL30-36-36-76' },
                                { key: 'Voltage', value: '1.35V' },
                                { key: 'Performance Profile', value: 'Intel XMP 3.0 & AMD EXPO' }
                            ];
                        } else if (type === 'storage') {
                            this.specs = [
                                { key: 'Drive Capacity', value: '2TB NVMe SSD' },
                                { key: 'Interface', value: 'PCIe Gen 4.0 x4, NVMe 1.4' },
                                { key: 'Form Factor', value: 'M.2 2280' },
                                { key: 'Sequential Read Speed', value: '7,400 MB/s' },
                                { key: 'Sequential Write Speed', value: '6,500 MB/s' },
                                { key: 'Flash Memory Type', value: '3D TLC NAND' },
                                { key: 'TBW Endurance', value: '1,200 TBW' }
                            ];
                        } else if (type === 'gpu') {
                            this.specs = [
                                { key: 'Graphics Chipset', value: 'NVIDIA GeForce RTX 4070 Ti' },
                                { key: 'VRAM Capacity', value: '12GB GDDR6X' },
                                { key: 'Memory Bus', value: '192-bit' },
                                { key: 'Power Connector', value: '1x 16-Pin (12VHPWR)' },
                                { key: 'Display Connectors', value: '3x DisplayPort 1.4a, 1x HDMI 2.1a' }
                            ];
                        } else if (type === 'clear') {
                            this.specs = [];
                        }
                    }
                }">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                            <i class="fas fa-sliders text-indigo-500"></i> Dynamic Hardware & Technical Specifications
                        </h3>

                        <!-- Preset Quick-Add Buttons -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <span class="text-[10px] text-slate-400 font-bold uppercase mr-1">Quick Presets:</span>
                            <button type="button" @click="loadPreset('cpu')" class="px-2 py-1 text-[11px] font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg border border-indigo-200/60 transition-colors inline-flex items-center gap-1">
                                <i class="fas fa-microchip text-[10px]"></i> + CPU
                            </button>
                            <button type="button" @click="loadPreset('ram')" class="px-2 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg border border-emerald-200/60 transition-colors inline-flex items-center gap-1">
                                <i class="fas fa-memory text-[10px]"></i> + RAM
                            </button>
                            <button type="button" @click="loadPreset('storage')" class="px-2 py-1 text-[11px] font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg border border-amber-200/60 transition-colors inline-flex items-center gap-1">
                                <i class="fas fa-hard-drive text-[10px]"></i> + Hard Disk / SSD
                            </button>
                            <button type="button" @click="loadPreset('gpu')" class="px-2 py-1 text-[11px] font-bold bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg border border-purple-200/60 transition-colors inline-flex items-center gap-1">
                                <i class="fas fa-tv text-[10px]"></i> + GPU Card
                            </button>
                            <button type="button" @click="addSpec('', '')" class="px-2 py-1 text-[11px] font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 rounded-lg border border-sky-200/60 transition-colors inline-flex items-center gap-1">
                                <i class="fas fa-plus text-[10px]"></i> + Custom Spec
                            </button>
                            <button type="button" @click="loadPreset('clear')" class="px-2 py-1 text-[11px] font-semibold text-slate-400 hover:text-rose-600 transition-colors" title="Clear all attributes">
                                Clear
                            </button>
                        </div>
                    </div>

                    <!-- Dynamic Attribute Inputs Container -->
                    <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                        <template x-if="specs.length === 0">
                            <div class="text-center py-6 text-slate-400 text-xs">
                                No technical specs added yet. Click one of the <strong class="text-indigo-600">Quick Presets</strong> above or <strong class="text-sky-600">+ Custom Spec</strong> to add attributes for CPU, RAM, Hard Disk, Motherboard, etc.
                            </div>
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <template x-for="(spec, index) in specs" :key="index">
                                <div class="flex items-center gap-2 p-2 bg-white rounded-xl border border-slate-200/80 shadow-2xs group hover:border-indigo-300 transition-all">
                                    <div class="w-2/5 shrink-0">
                                        <input 
                                            type="text" 
                                            name="spec_keys[]" 
                                            x-model="spec.key" 
                                            placeholder="Spec Label (e.g. Speed)" 
                                            class="w-full text-xs font-bold text-slate-700 bg-slate-50/60 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" 
                                            required
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <input 
                                            type="text" 
                                            name="spec_values[]" 
                                            x-model="spec.value" 
                                            placeholder="Value (e.g. 6000MHz CL30)" 
                                            class="w-full text-xs text-slate-900 bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" 
                                            required
                                        />
                                    </div>
                                    <button 
                                        type="button" 
                                        @click="removeSpec(index)" 
                                        class="p-1.5 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Remove specification attribute"
                                    >
                                        <i class="fas fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100" />

                <!-- Pricing & Quantity Inventory Section -->
                <div x-data="{ cost: {{ old('unit_cost', 0) }}, retail: {{ old('retail_price', 0) }} }">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-2">
                        <i class="fas fa-tags text-indigo-500"></i> Pricing & Inventory Quantity Levels
                    </h3>

                    <!-- Live Retail Price Warning Alert Banner -->
                    <div x-show="parseFloat(retail) <= parseFloat(cost) && parseFloat(cost) > 0" x-cloak class="mb-3 p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-900 flex items-center justify-between shadow-2xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-triangle-exclamation text-amber-600 text-sm"></i>
                            <span><strong>Pricing Warning:</strong> Retail Price ($<span x-text="parseFloat(retail || 0).toFixed(2)"></span>) must be <strong>higher than Cost Price</strong> ($<span x-text="parseFloat(cost || 0).toFixed(2)"></span>). Selling at or below cost is not permitted.</span>
                        </div>
                        <span class="text-[10px] bg-amber-200/60 font-bold px-2 py-0.5 rounded text-amber-800 uppercase">Margin Alert</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
                        <!-- Cost Price -->
                        <x-forms.input 
                            type="number" 
                            step="0.01" 
                            name="unit_cost" 
                            label="Cost Price ($ USD)" 
                            placeholder="e.g. 280.00" 
                            :value="old('unit_cost', 0.00)" 
                            x-model="cost"
                            required 
                        />

                        <!-- Retail Price -->
                        <x-forms.input 
                            type="number" 
                            step="0.01" 
                            name="retail_price" 
                            label="Retail Price ($ USD)" 
                            placeholder="e.g. 350.00" 
                            :value="old('retail_price', 0.00)" 
                            x-model="retail"
                            required 
                        />

                        <!-- Initial Quantity -->
                        <x-forms.input 
                            type="number" 
                            name="quantity" 
                            label="Available Qty" 
                            placeholder="e.g. 100" 
                            :value="old('quantity', 100)" 
                            required 
                        />

                        <!-- Reserved Quantity -->
                        <x-forms.input 
                            type="number" 
                            name="reserved_quantity" 
                            label="Reserved Qty" 
                            placeholder="e.g. 0" 
                            :value="old('reserved_quantity', 0)" 
                        />

                        <!-- Min Reorder Level -->
                        <x-forms.input 
                            type="number" 
                            name="min_reorder_level" 
                            label="Low Stock Min" 
                            placeholder="e.g. 15" 
                            :value="old('min_reorder_level', 15)" 
                            required 
                        />

                        <!-- Max Capacity -->
                        <x-forms.input 
                            type="number" 
                            name="max_capacity" 
                            label="Max Rack Cap" 
                            placeholder="e.g. 500" 
                            :value="old('max_capacity', 500)" 
                            required 
                        />
                    </div>

                    <!-- Multi-Tier Volume Pricing Builder (Alpine.js) -->
                    <div class="mt-6 bg-slate-50/80 p-4 rounded-xl border border-slate-200/80" x-data="{
                        tiers: [
                            { min_qty: 1, max_qty: 5, price: 350.00 },
                            { min_qty: 6, max_qty: 100, price: 320.00 },
                            { min_qty: 101, max_qty: '', price: 290.00 }
                        ],
                        addTier() {
                            let lastMax = this.tiers.length > 0 && this.tiers[this.tiers.length - 1].max_qty ? parseInt(this.tiers[this.tiers.length - 1].max_qty) : 1;
                            this.tiers.push({ min_qty: lastMax + 1, max_qty: '', price: 0.00 });
                        },
                        removeTier(index) {
                            if (this.tiers.length > 1) {
                                this.tiers.splice(index, 1);
                            }
                        }
                    }">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-layer-group text-indigo-600"></i> Multi-Tier Volume Pricing (Bulk Quantity Breaks)
                                </h4>
                                <p class="text-[11px] text-slate-500">Set dynamic wholesale pricing tiers based on purchase quantity (e.g., 1-5 @ $350, 6-100 @ $320, 101+ @ $290)</p>
                            </div>
                            <button type="button" @click="addTier()" class="px-3 py-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg border border-indigo-200/60 inline-flex items-center gap-1">
                                <i class="fas fa-plus"></i> Add Price Tier
                            </button>
                        </div>

                        <div class="space-y-2">
                            <template x-for="(tier, index) in tiers" :key="index">
                                <div class="flex items-center gap-4 bg-white px-3.5 py-2 rounded-lg border border-slate-200 text-xs shadow-2xs">
                                    <span class="w-16 font-extrabold text-indigo-700 bg-indigo-50/80 py-1 px-2 rounded-md text-center shrink-0 border border-indigo-100" x-text="'Tier ' + (index + 1)"></span>
                                    
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="flex items-center gap-2 flex-1">
                                            <label class="text-[11px] font-bold text-slate-600 whitespace-nowrap shrink-0">Min Qty:</label>
                                            <input type="number" name="tier_min_qty[]" x-model="tier.min_qty" min="1" placeholder="1" required class="w-full text-xs rounded-lg border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-1.5 px-2.5 font-semibold text-slate-800 bg-white shadow-2xs" />
                                        </div>
                                        
                                        <div class="flex items-center gap-2 flex-1">
                                            <label class="text-[11px] font-bold text-slate-600 whitespace-nowrap shrink-0">Max Qty (+):</label>
                                            <input type="number" name="tier_max_qty[]" x-model="tier.max_qty" min="1" placeholder="Blank for unlimited (+)" class="w-full text-xs rounded-lg border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-1.5 px-2.5 font-semibold text-slate-800 bg-white shadow-2xs" />
                                        </div>

                                        <div class="flex items-center gap-2 flex-1">
                                            <label class="text-[11px] font-bold text-slate-600 whitespace-nowrap shrink-0">Unit Price ($):</label>
                                            <input type="number" step="0.01" name="tier_price[]" x-model="tier.price" min="0" placeholder="e.g. 350.00" required class="w-full text-xs font-extrabold text-indigo-900 rounded-lg border border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 py-1.5 px-2.5 bg-white shadow-2xs" />
                                        </div>
                                    </div>

                                    <button type="button" @click="removeTier(index)" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-md hover:bg-rose-50 transition-colors shrink-0" title="Remove Tier">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100" />

                <!-- Location & Extra Notes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.input 
                        name="rack_location" 
                        label="Rack / Aisle / Bin Location" 
                        placeholder="e.g. RACK-CPU-02-B" 
                        :value="old('rack_location')" 
                    />

                    <x-forms.textarea 
                        name="notes" 
                        label="Inventory Line Notes" 
                        placeholder="Special storage instructions or batch numbers..." 
                        rows="2" 
                    >{{ old('notes') }}</x-forms.textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-100">
                <x-forms.button href="{{ route('admin.stocks.index') }}" variant="secondary">
                    Cancel
                </x-forms.button>
                <x-forms.button type="submit" variant="primary" icon="fas fa-check" class="!bg-indigo-600 hover:!bg-indigo-700">
                    Save Product & Stock Item
                </x-forms.button>
            </div>
        </x-forms.form>
    </x-forms.card>

</div>
@endsection
