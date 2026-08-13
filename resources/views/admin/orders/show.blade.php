@extends('admin.layout.app')

@section('title', 'Order ' . $order->order_number . ' - Wholesale Workspace')

@push('styles')
    <style>
        @media print {

            aside,
            header,
            footer,
            .no-print,
            button,
            .print\:hidden,
            #sidebarBackdrop,
            #adminSidebar {
                display: none !important;
            }

            #mainContentWrapper {
                padding-left: 0 !important;
            }

            main {
                padding: 0 !important;
            }

            body {
                background-color: #ffffff !important;
            }

            .shadow-xs,
            .shadow-sm,
            .shadow {
                box-shadow: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Order #{{ $order->order_number }}</h1>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Placed on
                    {{ $order->order_date ? $order->order_date->format('F d, Y \a\t h:i A') : 'N/A' }}</p>
            </div>
            <div class="flex items-center gap-3 print:hidden no-print">
                <a href="{{ $order->order_source === 'frontend' ? route('admin.orders.registered') : route('admin.orders.index') }}"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Orders
                </a>
                @if (auth()->user()
                        ?->canDo(['orders.edit', 'manage_orders']))
                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                        class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-semibold rounded-xl border border-amber-200 transition-colors">
                        <i class="fas fa-pen mr-1"></i> Edit Order
                    </a>
                @endif
                @if (auth()->user()
                        ?->canDo(['orders.delete', 'manage_orders']))
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Are you sure you want to delete order #{{ $order->order_number }}? Stock quantities will be restored.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200 transition-colors">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                @endif
                <button onclick="window.print()"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all">
                    <i class="fas fa-print mr-1"></i> Print Invoice
                </button>
            </div>
        </div>

        <!-- Quick Status Update Card -->
        @if (auth()->user()
                ?->canDo(['orders.edit', 'manage_orders']))
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs print:hidden no-print">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST"
                    class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                            <i class="fas fa-sliders"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Update Fulfillment & Payment Status</h4>
                            <p class="text-xs text-slate-500">Change order processing stage or payment status for this
                                wholesale order</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="status"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>

                        <select name="payment_status"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                            <option value="partially_paid"
                                {{ $order->payment_status === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>

                        <button type="submit"
                            class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Customer & Order Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Customer Profile Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">B2B Wholesale Customer</h3>
                    @if (!empty($order->customer->tier))
                        <span
                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            {{ $order->customer->tier }}
                        </span>
                    @endif
                </div>

                <div>
                    <h4 class="text-base font-bold text-slate-900">{{ $order->customer->name ?? 'Unknown Customer' }}</h4>
                    <p class="text-xs font-semibold text-indigo-600">{{ $order->customer->company_name ?? 'Individual' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-1">Code: <span
                            class="font-mono font-bold">{{ $order->customer->customer_code ?? 'N/A' }}</span></p>
                </div>

                <div class="space-y-2 text-xs text-slate-600 border-t border-slate-100 pt-3">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Email:</span>
                        <span class="font-semibold">{{ $order->customer->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Phone:</span>
                        <span class="font-semibold">{{ $order->customer->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Wholesale Discount:</span>
                        <span class="font-bold text-emerald-600">{{ $order->customer->wholesale_discount ?? 0 }}%
                            OFF</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Credit Limit:</span>
                        <span class="font-semibold">${{ number_format($order->customer->credit_limit ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary Details -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4 md:col-span-2">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Payment & Logistics Details</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $order->payment_badge }}">
                        Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">Payment Method</span>
                        <span
                            class="font-bold text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl inline-block border border-indigo-100">{{ $order->payment_method ?? 'Not Specified' }}</span>
                        @if(!empty($order->payment_terms))
                            <span class="text-[11px] text-slate-500 block mt-1">Terms: {{ $order->payment_terms }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1">Shipping Address</span>
                        <span
                            class="font-semibold text-slate-800 block">{{ $order->shipping_address ?? 'Standard Warehouse Delivery' }}</span>
                    </div>
                </div>

                @if (!empty($order->notes))
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs">
                        <span class="font-bold text-slate-700 block mb-1">Logistics & Order Notes:</span>
                        <p class="text-slate-600">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Breakdown Table -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Itemized Order Breakdown</h3>
                <span class="text-xs text-slate-500 font-semibold">{{ $order->items->count() }} Item(s)</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="px-5 py-3">#</th>
                            <th class="px-5 py-3">Product Item Name</th>
                            <th class="px-5 py-3 text-center">Quantity</th>
                            <th class="px-5 py-3 text-right">Unit Price ($)</th>
                            <th class="px-5 py-3 text-right">Subtotal ($)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach ($order->items as $index => $item)
                            <tr>
                                <td class="px-5 py-3 text-slate-400 font-mono">{{ $index + 1 }}</td>
                                <td class="px-5 py-3 font-bold text-slate-900">
                                    {{ $item->product_name }}
                                </td>
                                <td class="px-5 py-3 text-center font-bold">{{ number_format($item->quantity) }}</td>
                                <td class="px-5 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-slate-900">
                                    ${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Summary Footer -->
            <div class="p-6 bg-slate-50/50 border-t border-slate-200 flex flex-col items-end space-y-2 text-xs">
                <div class="flex justify-between w-64 text-slate-600">
                    <span>Gross Subtotal:</span>
                    <span class="font-bold">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between w-64 text-emerald-600 font-semibold">
                        <span>Wholesale Discount Rate:</span>
                        <span>-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between w-64 text-slate-600">
                    <span>Est. Tax (5%):</span>
                    <span>+${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="flex justify-between w-64 text-base font-black text-slate-900 border-t border-slate-200 pt-2">
                    <span>Final Order Total:</span>
                    <span class="text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
