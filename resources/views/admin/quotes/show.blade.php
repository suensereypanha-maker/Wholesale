@extends('admin.layout.app')

@section('title', 'Quote Details - ' . $quote->quote_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight font-monospace">
                    {{ $quote->quote_number }}
                </h1>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $quote->status_badge }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    {{ $quote->status_label }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Submitted on {{ $quote->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.quotes.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to Quotes
            </a>

            <a href="{{ route('admin.quotes.edit', $quote->id) }}" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-xl border border-amber-200 transition-colors">
                <i class="fas fa-pen mr-1"></i> Edit Quote
            </a>

            @if($quote->status !== 'converted')
                <form action="{{ route('admin.quotes.convert-to-order', $quote->id) }}" method="POST" class="inline" onsubmit="return confirm('Convert quote #{{ $quote->quote_number }} into an active Wholesale Order?');">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <i class="fas fa-cart-plus mr-1"></i> Convert to Order
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.quotes.destroy', $quote->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete quote #{{ $quote->quote_number }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200 transition-colors" title="Delete Quote">
                    <i class="fas fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl text-xs font-bold text-emerald-800 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-check text-emerald-500 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900"><i class="fas fa-xmark"></i></button>
        </div>
    @endif

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Columns: Quote Details & Quick Pricing Response Form -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Item & Financial Summary Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Requested Item Breakdown</h3>
                    <span class="text-xs font-bold text-slate-500">Inquiry ID: #{{ $quote->id }}</span>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200/80 gap-4">
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Product Name</span>
                        <h4 class="text-base font-bold text-slate-900 mt-0.5">{{ $quote->product_name }}</h4>
                        @if($quote->stock)
                            <div class="text-xs text-indigo-600 font-semibold mt-1">
                                Linked Stock SKU: <strong>{{ $quote->stock->sku }}</strong> &bull; Available Stock: {{ $quote->stock->quantity }} units
                            </div>
                        @endif
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Quantity Requested</span>
                        <div class="text-xl font-black text-slate-900">{{ number_format($quote->quantity) }} <span class="text-xs font-normal text-slate-500">units</span></div>
                    </div>
                </div>

                <!-- Price Comparison Row -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-4 text-xs">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60">
                        <span class="text-slate-400 font-bold uppercase text-[10px]">Buyer Target Price</span>
                        <div class="text-base font-bold text-slate-800 mt-1">
                            {{ $quote->target_price ? '$' . number_format($quote->target_price, 2) : 'Not Specified' }}
                        </div>
                    </div>

                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200">
                        <span class="text-emerald-600 font-bold uppercase text-[10px]">Admin Offered Price</span>
                        <div class="text-base font-black text-emerald-700 mt-1">
                            {{ $quote->offered_price ? '$' . number_format($quote->offered_price, 2) : 'Pending Offer' }}
                        </div>
                    </div>

                    <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-200">
                        <span class="text-indigo-600 font-bold uppercase text-[10px]">Est. Total Offer Value</span>
                        <div class="text-base font-black text-indigo-700 mt-1">
                            @if($quote->offered_price)
                                ${{ number_format($quote->offered_price * $quote->quantity, 2) }}
                            @elseif($quote->target_price)
                                ${{ number_format($quote->target_price * $quote->quantity, 2) }}
                            @else
                                $0.00
                            @endif
                        </div>
                    </div>
                </div>

                @if($quote->message)
                    <div class="border-t border-slate-100 pt-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Customer Message & Requirements</h4>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 text-xs text-slate-800 whitespace-pre-line leading-relaxed">
                            {{ $quote->message }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Quick Response / Price Offer Form -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fas fa-tag text-emerald-600"></i>
                        <span>Respond with Price Offer & Status</span>
                    </h3>
                </div>

                <form action="{{ route('admin.quotes.update-status', $quote->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Offered Unit Price ($) *</label>
                            <input 
                                type="number" 
                                step="0.01" 
                                name="offered_price" 
                                value="{{ old('offered_price', $quote->offered_price) }}" 
                                placeholder="e.g. 1200.00"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-emerald-700 focus:bg-white focus:border-emerald-500 focus:outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Update Status *</label>
                            <select name="status" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
                                <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="under_review" {{ $quote->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="quoted" {{ $quote->status === 'quoted' ? 'selected' : '' }}>Quote Offered</option>
                                <option value="approved" {{ $quote->status === 'approved' ? 'selected' : '' }}>Approved by Buyer</option>
                                <option value="converted" {{ $quote->status === 'converted' ? 'selected' : '' }}>Converted to Order</option>
                                <option value="rejected" {{ $quote->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Internal Admin Notes</label>
                        <textarea name="admin_notes" rows="3" placeholder="Supplier pricing notes, margin estimate, or email summary..." class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">{{ old('admin_notes', $quote->admin_notes) }}</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                            Save Price Offer & Update Status
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Column: Buyer Info & Actions -->
        <div class="space-y-6">

            <!-- Buyer Profile Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider border-b border-slate-100 pb-2">Buyer Information</h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium">Company Name:</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $quote->company_name }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium">Contact Person:</span>
                        <div class="font-semibold text-slate-800 mt-0.5">{{ $quote->contact_name }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium">Email:</span>
                        <div class="font-semibold text-indigo-600 mt-0.5">{{ $quote->email }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium">Phone:</span>
                        <div class="font-semibold text-slate-800 mt-0.5">{{ $quote->phone }}</div>
                    </div>

                    @if($quote->customer)
                        <div class="border-t border-slate-100 pt-3">
                            <span class="text-slate-400 font-medium block mb-1">Linked B2B Client Account:</span>
                            <a href="{{ route('admin.customers.show', $quote->customer->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-bold transition-colors">
                                <i class="fas fa-building"></i>
                                <span>{{ $quote->customer->name }} ({{ $quote->customer->tier }})</span>
                            </a>
                        </div>
                    @else
                        <div class="border-t border-slate-100 pt-3 text-[11px] text-amber-700 bg-amber-50 p-2.5 rounded-lg border border-amber-200 font-medium">
                            <i class="fas fa-info-circle me-1"></i> Direct buyer inquiry. Converting to order will automatically register a customer profile.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Order Conversion Card -->
            @if($quote->status !== 'converted')
                <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white rounded-2xl p-6 space-y-4 shadow-sm">
                    <div>
                        <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Quick Action</span>
                        <h4 class="text-lg font-bold text-white mt-1">Convert to Wholesale Order</h4>
                        <p class="text-xs text-slate-300 mt-1">Transform this inquiry into an active B2B Wholesale Order with auto-generated invoice.</p>
                    </div>

                    <form action="{{ route('admin.quotes.convert-to-order', $quote->id) }}" method="POST" onsubmit="return confirm('Convert quote #{{ $quote->quote_number }} into an active Wholesale Order?');">
                        @csrf
                        <button type="submit" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-2">
                            <i class="fas fa-cart-plus"></i>
                            <span>Create Wholesale Order Now</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-purple-50 border border-purple-200 rounded-2xl p-5 text-center space-y-2">
                    <i class="fas fa-circle-check text-purple-600 text-2xl"></i>
                    <h5 class="text-sm font-bold text-purple-900">Inquiry Converted</h5>
                    <p class="text-xs text-purple-700">This quote inquiry was successfully converted to a wholesale order.</p>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
