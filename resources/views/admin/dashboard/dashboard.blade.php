@extends('admin.layout.app')

@section('title', 'Wholesale Management Dashboard')

@section('content')

    <!-- Page Header Section -->
    @include('admin.components.page-header', [
        'title' => 'B2B Wholesale Dashboard',
        'subtitle' => 'Manage bulk orders, client accounts, tier pricing, and warehouse inventory',
        'breadcrumbs' => ['Wholesale' => '#', 'Dashboard' => route('admin.dashboard')]
    ])

    <!-- 1. KPI Statistics Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @include('admin.components.stat-card', [
            'title' => 'Monthly Revenue',
            'value' => $stats['total_revenue']['value'],
            'change' => $stats['total_revenue']['change'],
            'isPositive' => $stats['total_revenue']['is_positive'],
            'period' => $stats['total_revenue']['period'],
            'icon' => $stats['total_revenue']['icon'],
            'bg' => $stats['total_revenue']['bg']
        ])

        @include('admin.components.stat-card', [
            'title' => 'Wholesale Orders',
            'value' => $stats['wholesale_orders']['value'],
            'change' => $stats['wholesale_orders']['change'],
            'isPositive' => $stats['wholesale_orders']['is_positive'],
            'period' => $stats['wholesale_orders']['period'],
            'icon' => $stats['wholesale_orders']['icon'],
            'bg' => $stats['wholesale_orders']['bg']
        ])

        @include('admin.components.stat-card', [
            'title' => 'Active B2B Clients',
            'value' => $stats['active_b2b_buyers']['value'],
            'change' => $stats['active_b2b_buyers']['change'],
            'isPositive' => $stats['active_b2b_buyers']['is_positive'],
            'period' => $stats['active_b2b_buyers']['period'],
            'icon' => $stats['active_b2b_buyers']['icon'],
            'bg' => $stats['active_b2b_buyers']['bg']
        ])

        @include('admin.components.stat-card', [
            'title' => 'Pending Approvals',
            'value' => $stats['pending_approvals']['value'],
            'change' => $stats['pending_approvals']['change'],
            'isPositive' => $stats['pending_approvals']['is_positive'],
            'period' => $stats['pending_approvals']['period'],
            'icon' => $stats['pending_approvals']['icon'],
            'bg' => $stats['pending_approvals']['bg']
        ])
    </div>

    <!-- 2. Main Analytics & Quick Actions Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Revenue Analytics Chart Card (Spans 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Wholesale Revenue Performance</h3>
                    <p class="text-xs text-slate-500">Monthly breakdown of bulk orders vs credit term payouts</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span> 2026 Volume
                    </span>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-72 w-full">
                <canvas id="b2bRevenueChart"></canvas>
            </div>
        </div>

        <!-- Quick B2B Actions & Highlights Grid (Spans 1 col) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 tracking-tight mb-1">Quick Actions</h3>
                <p class="text-xs text-slate-500 mb-5">Common management tasks for B2B portal</p>

                <div class="space-y-3">
                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/70 hover:border-indigo-500/50 hover:bg-indigo-50/30 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <i class="fas fa-file-circle-plus"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800">Issue Custom Quote</span>
                                <span class="text-[11px] text-slate-500">Generate special bulk price</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-indigo-600 transition-colors"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/70 hover:border-emerald-500/50 hover:bg-emerald-50/30 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800">Approve New Buyer</span>
                                <span class="text-[11px] text-slate-500">Review 18 pending requests</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-emerald-600 transition-colors"></i>
                    </a>

                    <a href="#" class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200/70 hover:border-sky-500/50 hover:bg-sky-50/30 transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-sm font-bold group-hover:scale-105 transition-transform">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-slate-800">Adjust Tiered Rules</span>
                                <span class="text-[11px] text-slate-500">Update quantity discounts</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-xs text-slate-300 group-hover:text-sky-600 transition-colors"></i>
                    </a>
                </div>
            </div>

            <!-- B2B System Notice Card -->
            <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3">
                <i class="fas fa-shield-halved text-indigo-600 text-sm mt-0.5"></i>
                <div class="text-xs">
                    <p class="font-semibold text-slate-800">Net 30 Credit Audit</p>
                    <p class="text-slate-500 text-[11px] mt-0.5">2 accounts reached 85% credit utilization. Credit limits scheduled for review.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Recent Wholesale Orders Table & Top Accounts Side-by-Side -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Recent Orders Data Table (Spans 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Recent Bulk Orders</h3>
                    <p class="text-xs text-slate-500">Latest wholesale transactions & shipment status</p>
                </div>
                <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                    <span>View All Orders</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/70 border-b border-slate-100 text-slate-500 uppercase tracking-wider font-semibold text-[10px]">
                        <tr>
                            <th scope="col" class="py-3.5 px-6">Order ID</th>
                            <th scope="col" class="py-3.5 px-4">Client Company</th>
                            <th scope="col" class="py-3.5 px-4">Items Count</th>
                            <th scope="col" class="py-3.5 px-4">Total Value</th>
                            <th scope="col" class="py-3.5 px-4">Status</th>
                            <th scope="col" class="py-3.5 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $order['order_no'] }}
                                    <div class="text-[10px] text-slate-400 font-normal">{{ $order['date'] }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-bold text-slate-800">{{ $order['client_company'] }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $order['contact_person'] }}</div>
                                </td>
                                <td class="py-4 px-4 font-semibold text-slate-700">
                                    {{ number_format($order['items_count']) }} pcs
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-900">
                                    ${{ number_format($order['total_amount'], 2) }}
                                </td>
                                <td class="py-4 px-4">
                                    @include('admin.components.status-badge', ['status' => $order['order_status']])
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <button type="button" class="text-slate-400 hover:text-slate-600 p-1 rounded hover:bg-slate-100 transition-colors">
                                        <i class="fas fa-ellipsis-v text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side Widgets (Top B2B Accounts & Reorder Alerts) -->
        <div class="space-y-8">
            
            <!-- Top Wholesale Accounts -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">Top Wholesale Buyers</h3>
                    <span class="text-xs text-slate-400">By Annual Volume</span>
                </div>

                <div class="space-y-4">
                    @foreach($topBuyers as $buyer)
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl {{ $buyer['bg'] }} text-white font-bold flex items-center justify-center text-sm shadow-2xs">
                                    {{ $buyer['avatar'] }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800">{{ $buyer['name'] }}</span>
                                    <span class="text-[10px] font-semibold text-indigo-600">{{ $buyer['tier'] }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-slate-900">{{ $buyer['total_spent'] }}</div>
                                <div class="text-[10px] text-slate-400">Limit: {{ $buyer['credit_limit'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Warehouse Stock & Reorder Alerts -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-box-open text-rose-500 text-sm"></i>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight">Inventory Reorder Alerts</h3>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-100 text-rose-700 rounded-full">Action Needed</span>
                </div>

                <div class="space-y-3">
                    @foreach($lowStockAlerts as $item)
                        <div class="p-3 rounded-xl border border-slate-200/70 bg-slate-50/50">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-bold font-mono text-slate-400">{{ $item['sku'] }}</span>
                                @include('admin.components.status-badge', ['status' => $item['status']])
                            </div>
                            <p class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item['product_name'] }}</p>
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-500">
                                <span>Stock: <strong class="text-slate-900">{{ $item['current_stock'] }} units</strong></span>
                                <span>Reorder at: {{ $item['min_reorder'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('b2bRevenueChart');
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [
                        {
                            label: 'Wholesale Sales ($)',
                            data: [68000, 84000, 79000, 105000, 112000, 128000, 135000, 148920],
                            borderColor: '#4f46e5',
                            borderWidth: 3,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.35,
                            pointBackgroundColor: '#4f46e5',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(context) {
                                    return ' Revenue: $' + context.raw.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b'
                            }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            },
                            ticks: {
                                font: { family: 'Inter', size: 11 },
                                color: '#64748b',
                                callback: function(value) {
                                    return '$' + (value / 1000) + 'k';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
