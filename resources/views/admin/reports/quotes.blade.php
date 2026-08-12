@extends('admin.layout.app')

@section('title', 'Quotes & Conversion Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                <i class="fas fa-handshake-angle"></i>
                <span>Reports & Analytics</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Quotes & Bids Conversion Report</h1>
            <p class="text-sm text-slate-500 mt-0.5">Tracking requested bulk quotes, offer approvals, and RFQ-to-order conversion metrics.</p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-xs flex items-center gap-2">
                <i class="fas fa-print text-slate-400"></i>
                <span>Print Report</span>
            </button>
            <a href="#" class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6">
            <a href="{{ route('admin.reports.sales') }}" class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-chart-line"></i>
                Sales & Revenue
            </a>
            <a href="{{ route('admin.reports.inventory') }}" class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-boxes-stacked"></i>
                Inventory Valuation
            </a>
            <a href="{{ route('admin.reports.customers') }}" class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-users-gear"></i>
                Customer Analytics
            </a>
            <a href="{{ route('admin.reports.quotes') }}" class="py-3 px-1 border-b-2 border-indigo-600 font-bold text-sm text-indigo-600 flex items-center gap-2">
                <i class="fas fa-handshake-angle"></i>
                Quotes & Conversion
            </a>
        </nav>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total RFQs Received</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900">{{ $quoteStats['total_rfqs'] }}</span>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">RFQ Volume</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Converted Orders</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600">{{ $quoteStats['converted_orders'] }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Successful</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Pending Review</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600">{{ $quoteStats['pending_review'] }}</span>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Action Needed</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Conversion Rate</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-purple-600">{{ $quoteStats['conversion_rate'] }}</span>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">Performance</span>
            </div>
        </div>
    </div>

    <!-- Quotes Conversion Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">RFQs & Pricing Proposals Log</h2>
                <p class="text-xs text-slate-500">Summary of recent price quote negotiations</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Quote Reference</th>
                        <th class="px-5 py-3.5">Requested By</th>
                        <th class="px-5 py-3.5 text-center">Units Requested</th>
                        <th class="px-5 py-3.5 text-right">Target Budget</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($quotesSummary as $quote)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-indigo-600">RFQ-{{ str_pad($quote->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $quote->company_name ?? $quote->user->name ?? 'Wholesale Client' }}</td>
                        <td class="px-5 py-3.5 text-center font-bold text-slate-700">{{ $quote->quantity ?? 100 }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-slate-900">${{ number_format($quote->target_price ?? 5000.00, 2) }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold capitalize
                                {{ $quote->status === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ in_array($quote->status, ['pending', 'under_review']) ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                {{ $quote->status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                {{ str_replace('_', ' ', $quote->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400">No active quote reports available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
