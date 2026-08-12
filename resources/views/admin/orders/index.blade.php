@extends('admin.layout.app')

@section('title', 'Wholesale Customer Orders - Admin Workspace')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-3">
                <span>{{ isset($isCustomerRegisterOrders) ? 'Customer Register Orders (Frontend)' : 'Customer Partner Orders' }}</span>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    {{ isset($isCustomerRegisterOrders) ? 'Frontend Storefront' : 'B2B Wholesale Procurement' }}
                </span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ isset($isCustomerRegisterOrders) ? 'Manage orders placed online by registered frontend customers' : 'Manage corporate B2B partner bulk orders, contract payment terms, and fulfillment pipeline' }}
            </p>
        </div>
        <div>
            <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-xs hover:shadow transition-all">
                <i class="fas fa-plus text-xs"></i>
                <span>Create B2B Wholesale Order</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-slate-400 tracking-wider">Total Orders</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($totalOrdersCount) }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-slate-400 tracking-wider">Pending & Processing</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($pendingOrdersCount + $processingOrdersCount) }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-truck-fast"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-slate-400 tracking-wider">Completed Deliveries</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ number_format($deliveredOrdersCount) }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">
                <i class="fas fa-vault"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-slate-400 tracking-wider">Total Revenue</p>
                <h3 class="text-2xl font-black text-slate-900 mt-0.5">${{ number_format($totalSalesAmount, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="border-b border-slate-200 flex gap-4 text-xs font-bold">
        <a href="{{ route('admin.orders.index') }}" class="pb-3 border-b-2 transition-colors {{ !isset($isCustomerRegisterOrders) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-handshake mr-1.5"></i> Customer Partner Orders
        </a>
        <a href="{{ route('admin.orders.registered') }}" class="pb-3 border-b-2 transition-colors {{ isset($isCustomerRegisterOrders) ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
            <i class="fas fa-user-plus mr-1.5"></i> Customer Register Orders (Frontend)
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ isset($isCustomerRegisterOrders) ? route('admin.orders.registered') : route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by order #, customer name, company or code..." 
                    class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none transition-all"
                />
            </div>
            <div>
                <select name="status" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">All Fulfillment Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <select name="payment_status" onchange="this.form.submit()" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
                    <option value="">All Payment Statuses</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partially_paid" {{ request('payment_status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                    <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Orders Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider">
                        <th class="px-5 py-3.5">Order Info</th>
                        <th class="px-5 py-3.5">Wholesale Customer & Partner</th>
                        <th class="px-5 py-3.5">Order Value & Discount</th>
                        <th class="px-5 py-3.5">Payment Status</th>
                        <th class="px-5 py-3.5">Order Status</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <!-- Order Number & Date -->
                            <td class="px-5 py-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-indigo-600 hover:underline text-sm">
                                    {{ $order->order_number }}
                                </a>
                                @if($order->order_source === 'frontend')
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200 ml-1">
                                        <i class="fas fa-globe text-[9px] mr-1"></i>Frontend
                                    </span>
                                @else
                                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 ml-1">
                                        <i class="fas fa-handshake text-[9px] mr-1"></i>Partner Order
                                    </span>
                                @endif
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="far fa-calendar-alt mr-1"></i>{{ $order->order_date ? $order->order_date->format('M d, Y') : 'N/A' }}
                                </p>
                            </td>

                            <!-- Customer Profile -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shadow-xs border border-slate-200">
                                        {{ strtoupper(substr($order->customer->name ?? 'C', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs">
                                            {{ $order->customer->name ?? 'Unknown Customer' }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-2">
                                            <span>{{ $order->customer->company_name ?? 'Individual' }}</span>
                                            @if(!empty($order->customer->tier))
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                    {{ $order->customer->tier }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Total & Discount -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900 text-sm">${{ number_format($order->total_amount, 2) }}</div>
                                <div class="text-[11px] text-slate-400">
                                    Subtotal: ${{ number_format($order->subtotal, 2) }}
                                    @if($order->discount_amount > 0)
                                        <span class="text-emerald-600 font-semibold">(-${{ number_format($order->discount_amount, 2) }})</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Payment Status -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->payment_badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">Terms: {{ $order->payment_terms ?? 'Standard' }}</p>
                            </td>

                            <!-- Order Fulfillment Status -->
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-700 font-semibold rounded-lg transition-colors text-xs" title="View Details">
                                        <i class="fas fa-eye"></i>
                                        <span>Details</span>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold rounded-lg transition-colors text-xs" title="Edit Order">
                                        <i class="fas fa-pen"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete order #{{ $order->order_number }}? Stock quantities will be restored.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-colors text-xs" title="Delete Order">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                <p class="font-bold text-slate-600 text-sm">No {{ isset($isCustomerRegisterOrders) ? 'Customer Register Frontend' : 'Wholesale Customer Partner' }} Orders Found</p>
                                <p class="text-xs mt-1">Try adjusting search query or filter options.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
