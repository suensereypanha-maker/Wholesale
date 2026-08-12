@extends('admin.layout.app')

@section('title', 'Sales & Revenue Report')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
                <i class="fas fa-chart-line"></i>
                <span>Reports & Analytics</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Sales & Revenue Reports</h1>
            <p class="text-sm text-slate-500 mt-0.5">Comprehensive overview of wholesale sales performance, revenue channels, and margin insights.</p>
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

    <!-- Navigation Tabs for Reports -->
    <div class="border-b border-slate-200">
        <nav class="flex space-x-6">
            <a href="{{ route('admin.reports.sales') }}" class="py-3 px-1 border-b-2 border-indigo-600 font-bold text-sm text-indigo-600 flex items-center gap-2">
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
            <a href="{{ route('admin.reports.quotes') }}" class="py-3 px-1 border-b-2 border-transparent font-medium text-sm text-slate-500 hover:text-slate-700 hover:border-slate-300 flex items-center gap-2">
                <i class="fas fa-handshake-angle"></i>
                Quotes & Conversion
            </a>
        </nav>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Sales</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-sm"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900">${{ number_format($salesStats['total_sales'], 2) }}</span>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                    <i class="fas fa-arrow-up-right"></i>
                    <span>+14.8% vs last month</span>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Completed Orders</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-bag-shopping text-sm"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900">{{ $salesStats['completed_orders'] }}</span>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-indigo-600 font-bold">
                    <i class="fas fa-arrow-up-right"></i>
                    <span>+8.2% order volume</span>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Avg Order Value</span>
                <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-calculator text-sm"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900">${{ number_format($salesStats['average_order'], 2) }}</span>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-purple-600 font-bold">
                    <i class="fas fa-chart-line"></i>
                    <span>Bulk wholesale average</span>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Gross Profit Margin</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i class="fas fa-percent text-sm"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-slate-900">{{ $salesStats['gross_margin'] }}</span>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-amber-600 font-bold">
                    <i class="fas fa-check-circle"></i>
                    <span>Target margin achieved</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Category Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Trend Chart -->
        <div class="lg:col-span-2 p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Revenue Growth Trend</h2>
                    <p class="text-xs text-slate-500">Monthly sales volume and order total comparisons</p>
                </div>
                <span class="px-2.5 py-1 text-xs font-semibold text-indigo-700 bg-indigo-50 rounded-lg">2026 YTD</span>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="p-5 bg-white rounded-2xl border border-slate-200/80 shadow-xs">
            <h2 class="text-base font-bold text-slate-900 mb-1">Sales by Category</h2>
            <p class="text-xs text-slate-500 mb-4">Product category revenue share</p>

            <div class="space-y-4">
                @foreach($salesByCategory as $cat)
                <div>
                    <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                        <span>{{ $cat['name'] }}</span>
                        <span>${{ number_format($cat['amount'], 2) }} ({{ $cat['share'] }})</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $cat['share'] }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Recent Revenue Transactions</h2>
                <p class="text-xs text-slate-500">Latest completed wholesale order transactions</p>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ count($recentTransactions) }} items</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">Invoice ID</th>
                        <th class="px-5 py-3.5">B2B Customer</th>
                        <th class="px-5 py-3.5">Date</th>
                        <th class="px-5 py-3.5">Payment Method</th>
                        <th class="px-5 py-3.5 text-right">Amount</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($recentTransactions as $tx)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-semibold text-indigo-600">{{ $tx['id'] }}</td>
                        <td class="px-5 py-3.5 font-bold text-slate-800">{{ $tx['customer'] }}</td>
                        <td class="px-5 py-3.5 text-slate-500 text-xs">{{ $tx['date'] }}</td>
                        <td class="px-5 py-3.5 text-slate-600 text-xs">{{ $tx['payment'] }}</td>
                        <td class="px-5 py-3.5 text-right font-bold text-slate-900">${{ number_format($tx['amount'], 2) }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                {{ $tx['status'] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($monthlyData['labels']),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($monthlyData['sales']),
                    borderColor: '#4f46e5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
