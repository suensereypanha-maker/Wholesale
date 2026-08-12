@extends('admin.layout.app')

@section('title', 'Inventory Valuation Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                <i class="fas fa-boxes-stacked"></i>
                <span>Reports & Analytics</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Inventory & Valuation Report</h1>
            <p class="text-sm text-slate-500 mt-0.5">Real-time breakdown of warehouse stock levels, total asset valuation, and stock health.</p>
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
            <a href="{{ route('admin.reports.sales') }}" class="py-3 px-4 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-chart-line"></i>
                Sales & Revenue
            </a>
            <a href="{{ route('admin.reports.inventory') }}" class="py-3 px-4 border-b-2 border-indigo-600 font-bold text-sm text-indigo-600 flex items-center gap-2">
                <i class="fas fa-boxes-stacked"></i>
                Inventory Valuation
            </a>
            <a href="{{ route('admin.reports.customers') }}" class="py-3 px-4 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-users-gear"></i>
                Customer Analytics
            </a>
            <a href="{{ route('admin.reports.quotes') }}" class="py-3 px-4 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-handshake-angle"></i>
                Quotes & Conversion
            </a>
        </nav>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total Catalog SKUs</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-slate-900">{{ $inventoryStats['total_skus'] }}</span>
                <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">Active</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total Stock Valuation</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600">${{ number_format($inventoryStats['total_valuation'], 2) }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Asset Value</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Low Stock Alerts</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-amber-600">{{ $inventoryStats['low_stock_skus'] }}</span>
                <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Reorder Soon</span>
            </div>
        </div>
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold text-slate-500 uppercase">Out of Stock SKUs</span>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-black text-rose-600">{{ $inventoryStats['out_of_stock'] }}</span>
                <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">Critical</span>
            </div>
        </div>
    </div>

    <!-- Inventory Stock Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Inventory Stock & Valuation</h2>
                <p class="text-xs text-slate-500">Current warehouse levels and stock valuation</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Product SKU</th>
                        <th class="px-5 py-3.5">Product Name</th>
                        <th class="px-5 py-3.5">Warehouse</th>
                        <th class="px-5 py-3.5 text-right">Unit Price</th>
                        <th class="px-5 py-3.5 text-right">Available Qty</th>
                        <th class="px-5 py-3.5 text-right">Total Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($stocksList as $stock)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-indigo-600">{{ $stock->sku ?? 'SKU-'.$stock->id }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">{{ $stock->product_name ?? 'Wholesale Item #'.$stock->id }}</td>
                        <td class="px-5 py-3.5 text-slate-600 text-xs">{{ $stock->warehouse->name ?? 'Main Warehouse A' }}</td>
                        <td class="px-5 py-3.5 text-right font-medium text-slate-700">${{ number_format($stock->retail_price ?? 45.00, 2) }}</td>
                        <td class="px-5 py-3.5 text-right font-bold {{ ($stock->quantity ?? 0) <= 15 ? 'text-amber-600' : 'text-slate-900' }}">{{ $stock->quantity ?? 0 }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-emerald-600">${{ number_format(($stock->quantity ?? 0) * ($stock->retail_price ?? 45.00), 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400">No inventory records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
