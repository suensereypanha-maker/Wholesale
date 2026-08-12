@extends('admin.layout.app')

@section('title', 'B2B Quotes & Inquiries - Admin Workspace')

@section('content')
<div class="space-y-6" x-data="{ quickModalOpen: false, selectedQuote: null }">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-700 text-[11px] font-extrabold uppercase tracking-wider">B2B Sales</span>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Quotes & Inquiries Management</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Review custom pricing requests, dispatch competitive price offers, and convert buyer inquiries to active orders</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.quotes.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white text-xs font-bold rounded-xl shadow-xs hover:shadow-indigo-200 transition-all">
                <i class="fas fa-plus text-xs"></i>
                <span>Log New Quote Request</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl text-xs font-bold text-emerald-800 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-circle-check text-emerald-500 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 p-1"><i class="fas fa-xmark"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl text-xs font-bold text-rose-800 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2.5">
                <i class="fas fa-triangle-exclamation text-rose-500 text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 p-1"><i class="fas fa-xmark"></i></button>
        </div>
    @endif

    <!-- KPI Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <a href="{{ route('admin.quotes.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between group">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-indigo-600 transition-colors">Total Inquiries</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalQuotesCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-base group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-handshake-angle"></i>
            </div>
        </a>

        <a href="{{ route('admin.quotes.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between group {{ request('status') === 'pending' ? 'ring-2 ring-amber-500/20 border-amber-300 bg-amber-50/20' : '' }}">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-amber-600 transition-colors">Pending Review</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($pendingQuotesCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-base group-hover:bg-amber-500 group-hover:text-white transition-all">
                <i class="fas fa-clock"></i>
            </div>
        </a>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Requested Vol.</p>
                <h3 class="text-2xl font-black text-purple-600 mt-1">{{ number_format($totalQtyCount ?? 0) }} <span class="text-xs font-bold text-slate-400">pcs</span></h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-base">
                <i class="fas fa-cubes"></i>
            </div>
        </div>

        <a href="{{ route('admin.quotes.index', ['status' => 'quoted']) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between group {{ request('status') === 'quoted' ? 'ring-2 ring-blue-500/20 border-blue-300 bg-blue-50/20' : '' }}">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-blue-600 transition-colors">Price Offered</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1">{{ number_format($quotedCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-base group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i class="fas fa-tag"></i>
            </div>
        </a>

        <a href="{{ route('admin.quotes.index', ['status' => 'converted']) }}" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-between group {{ request('status') === 'converted' ? 'ring-2 ring-emerald-500/20 border-emerald-300 bg-emerald-50/20' : '' }}">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Converted Orders</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($convertedCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-base group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fas fa-cart-check"></i>
            </div>
        </a>
    </div>

    <!-- Interactive Filter & Search Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
        <!-- Quick Status Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-2 scrollbar-none border-b border-slate-100">
            <a href="{{ route('admin.quotes.index', array_filter(['search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ !request('status') ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span>All Statuses</span>
                <span class="px-1.5 py-0.5 rounded-md text-[10px] {{ !request('status') ? 'bg-indigo-700 text-indigo-100' : 'bg-slate-200 text-slate-700' }}">{{ $totalQuotesCount }}</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'pending', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <span>Pending Review</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'under_review', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'under_review' ? 'bg-purple-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span>
                <span>Under Review</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'quoted', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'quoted' ? 'bg-blue-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                <span>Quote Offered</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'approved', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'approved' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>Approved</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'converted', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'converted' ? 'bg-teal-700 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                <span>Converted</span>
            </a>

            <a href="{{ route('admin.quotes.index', array_filter(['status' => 'rejected', 'search' => request('search')])) }}" 
               class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all flex items-center gap-1.5 {{ request('status') === 'rejected' ? 'bg-rose-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                <span>Rejected</span>
            </a>
        </div>

        <!-- Search & Dropdown Grid Layout -->
        <form action="{{ route('admin.quotes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search Text Input -->
            <div class="md:col-span-7 relative w-full">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by Quote #, Company, Contact, Email, Product..." 
                    class="w-full pl-9 pr-9 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all"
                />
                @if(request('search'))
                    <a href="{{ route('admin.quotes.index', array_filter(['status' => request('status')])) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 text-xs" title="Clear Search">
                        <i class="fas fa-circle-xmark"></i>
                    </a>
                @endif
            </div>

            <!-- Status Dropdown Select -->
            <div class="md:col-span-3 w-full">
                <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 focus:outline-none transition-all" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="quoted" {{ request('status') === 'quoted' ? 'selected' : '' }}>Quote Offered</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved by Buyer</option>
                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted to Order</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-2 flex items-center justify-end gap-2 w-full">
                <button type="submit" class="flex-1 md:flex-initial px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition-all shadow-2xs flex items-center justify-center gap-1.5">
                    <i class="fas fa-filter text-slate-400 text-xs"></i>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.quotes.index') }}" class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5" title="Reset Filters">
                        <i class="fas fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quotes Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Quote Ref</th>
                        <th class="py-3.5 px-4">Buyer Company & Contact</th>
                        <th class="py-3.5 px-4">Requested Item</th>
                        <th class="py-3.5 px-4 text-center">Qty</th>
                        <th class="py-3.5 px-4 text-right">Target / Offered Price</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($quotes as $quote)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <!-- Quote Ref -->
                            <td class="py-4 px-4">
                                <a href="{{ route('admin.quotes.show', $quote->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-extrabold font-mono text-xs transition-colors">
                                    <i class="fas fa-file-invoice text-indigo-400 text-[10px]"></i>
                                    <span>{{ $quote->quote_number }}</span>
                                </a>
                                <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="far fa-clock text-[10px]"></i>
                                    <span>{{ $quote->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </td>

                            <!-- Buyer Company -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($quote->company_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $quote->company_name }}</div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5 mt-0.5">
                                            <span>{{ $quote->contact_name }}</span>
                                            <span>&bull;</span>
                                            <span class="text-slate-400">{{ $quote->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Requested Item -->
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ $quote->product_name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($quote->stock)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                            <i class="fas fa-barcode text-[9px]"></i>
                                            SKU: {{ $quote->stock->sku }}
                                        </span>
                                    @endif
                                    @if($quote->required_date)
                                        <span class="text-[10px] text-slate-400">Req: {{ $quote->required_date->format('M d, Y') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Qty -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 font-black text-slate-900 text-xs">
                                    {{ number_format($quote->quantity) }}
                                </span>
                            </td>

                            <!-- Price Offered / Target -->
                            <td class="py-4 px-4 text-right">
                                @if($quote->offered_price)
                                    <div class="font-black text-emerald-700 text-sm">${{ number_format($quote->offered_price, 2) }}</div>
                                    @if($quote->target_price)
                                        <div class="text-[10px] text-slate-400 line-through">Target: ${{ number_format($quote->target_price, 2) }}</div>
                                    @endif
                                @elseif($quote->target_price)
                                    <div class="font-bold text-slate-800">${{ number_format($quote->target_price, 2) }}</div>
                                    <div class="text-[10px] text-amber-600 font-semibold">Target Request</div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Open Quote</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $quote->status_badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $quote->status_label }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.quotes.show', $quote->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all" title="View Full Details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.quotes.edit', $quote->id) }}" class="p-2 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Edit Inquiry">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    @if($quote->status !== 'converted')
                                        <form action="{{ route('admin.quotes.convert-to-order', $quote->id) }}" method="POST" class="inline" onsubmit="return confirm('Convert quote #{{ $quote->quote_number }} into an active Wholesale Order?');">
                                            @csrf
                                            <button type="submit" class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-xl transition-all" title="Convert to Wholesale Order">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.quotes.destroy', $quote->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete quote #{{ $quote->quote_number }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all" title="Delete Quote">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-14 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mb-3">
                                        <i class="fas fa-handshake-angle"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-sm">No B2B Quotes or Inquiries Found</p>
                                    <p class="text-xs text-slate-400 mt-1">There are no quote requests matching your current filter criteria.</p>
                                    <a href="{{ route('admin.quotes.create') }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-2xs">
                                        <i class="fas fa-plus"></i>
                                        <span>Create New Quote Request</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($quotes->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $quotes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

