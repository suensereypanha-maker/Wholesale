<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the B2B Wholesale Management Dashboard.
     */
    public function index()
    {
        $pendingUserCount = User::where('status', 'pending')->orWhereNull('status')->count();
        $pendingCustomerCount = Customer::where('status', 'pending')->count();
        $totalPendingApprovals = $pendingUserCount + $pendingCustomerCount;

        // KPI Statistics Data for B2B Wholesale
        $stats = [
            'total_revenue' => [
                'value' => '$148,920.00',
                'change' => '+14.5%',
                'is_positive' => true,
                'period' => 'vs last month',
                'icon' => 'fas font-bold fa-dollar-sign',
                'bg' => 'bg-emerald-50 text-emerald-600',
            ],
            'wholesale_orders' => [
                'value' => '428',
                'change' => '+8.2%',
                'is_positive' => true,
                'period' => 'vs last month',
                'icon' => 'fas fa-boxes-stacked',
                'bg' => 'bg-indigo-50 text-indigo-600',
            ],
            'active_b2b_buyers' => [
                'value' => '1,240',
                'change' => '+5.1%',
                'is_positive' => true,
                'period' => 'active accounts',
                'icon' => 'fas fa-building',
                'bg' => 'bg-sky-50 text-sky-600',
            ],
            'pending_approvals' => [
                'value' => (string) $totalPendingApprovals,
                'change' => $totalPendingApprovals > 0 ? 'Action Required' : 'All Clear',
                'is_positive' => $totalPendingApprovals === 0,
                'period' => 'pending users & buyers',
                'icon' => 'fas fa-user-clock',
                'bg' => 'bg-amber-50 text-amber-600',
            ],
        ];

        // Recent Wholesale Orders Data
        $recentOrders = [
            [
                'order_no' => 'ORD-2026-8801',
                'client_company' => 'Global Retail Logistics Ltd',
                'contact_person' => 'Marcus Vance',
                'items_count' => 450,
                'total_amount' => 18450.00,
                'payment_status' => 'Paid',
                'order_status' => 'Processing',
                'date' => '2026-08-10 10:45 AM',
            ],
            [
                'order_no' => 'ORD-2026-8800',
                'client_company' => 'Apex Distribution Corp',
                'contact_person' => 'Sarah Connor',
                'items_count' => 1200,
                'total_amount' => 42100.00,
                'payment_status' => 'Net 30 Pending',
                'order_status' => 'Approved',
                'date' => '2026-08-10 09:30 AM',
            ],
            [
                'order_no' => 'ORD-2026-8799',
                'client_company' => 'Metro Superstores Direct',
                'contact_person' => 'David Kim',
                'items_count' => 310,
                'total_amount' => 9850.50,
                'payment_status' => 'Paid',
                'order_status' => 'Shipped',
                'date' => '2026-08-09 04:15 PM',
            ],
            [
                'order_no' => 'ORD-2026-8798',
                'client_company' => 'Horizon Traders Co',
                'contact_person' => 'Elena Rostova',
                'items_count' => 85,
                'total_amount' => 3400.00,
                'payment_status' => 'Pending Verification',
                'order_status' => 'Pending Quote',
                'date' => '2026-08-09 02:00 PM',
            ],
            [
                'order_no' => 'ORD-2026-8797',
                'client_company' => 'Nordic Wholesale Hub',
                'contact_person' => 'Lars Lindqvist',
                'items_count' => 920,
                'total_amount' => 31200.00,
                'payment_status' => 'Paid',
                'order_status' => 'Completed',
                'date' => '2026-08-08 11:20 AM',
            ],
        ];

        // Top Wholesale Buyer Accounts
        $topBuyers = [
            [
                'name' => 'Apex Distribution Corp',
                'tier' => 'Platinum Wholesale',
                'total_spent' => '$184,200.00',
                'credit_limit' => '$50,000.00',
                'credit_used' => '$42,100.00',
                'avatar' => 'A',
                'bg' => 'bg-indigo-600',
            ],
            [
                'name' => 'Global Retail Logistics',
                'tier' => 'Gold Tier B2B',
                'total_spent' => '$142,800.00',
                'credit_limit' => '$35,000.00',
                'credit_used' => '$0.00',
                'avatar' => 'G',
                'bg' => 'bg-emerald-600',
            ],
            [
                'name' => 'Nordic Wholesale Hub',
                'tier' => 'Platinum Wholesale',
                'total_spent' => '$128,500.00',
                'credit_limit' => '$60,000.00',
                'credit_used' => '$12,400.00',
                'avatar' => 'N',
                'bg' => 'bg-sky-600',
            ],
            [
                'name' => 'Metro Superstores Direct',
                'tier' => 'Silver Tier B2B',
                'total_spent' => '$94,300.00',
                'credit_limit' => '$25,000.00',
                'credit_used' => '$9,850.50',
                'avatar' => 'M',
                'bg' => 'bg-purple-600',
            ],
        ];

        // Low Stock / Warehouse Reorder Alerts
        $lowStockAlerts = [
            [
                'sku' => 'B2B-PROD-901',
                'product_name' => 'Ergonomic Executive Desk (Bulk Pack 5x)',
                'warehouse' => 'Main Warehouse A',
                'current_stock' => 14,
                'min_reorder' => 50,
                'status' => 'Critical Low',
            ],
            [
                'sku' => 'B2B-PROD-404',
                'product_name' => 'Wireless Keyboard & Mouse Combo (Carton 20x)',
                'warehouse' => 'West Hub B',
                'current_stock' => 28,
                'min_reorder' => 100,
                'status' => 'Reorder Recommended',
            ],
            [
                'sku' => 'B2B-PROD-112',
                'product_name' => '4K UltraHD Display 27-inch (Pallet 10x)',
                'warehouse' => 'Main Warehouse A',
                'current_stock' => 8,
                'min_reorder' => 30,
                'status' => 'Critical Low',
            ],
        ];

        return view('admin.dashboard.dashboard', compact('stats', 'recentOrders', 'topBuyers', 'lowStockAlerts'));
    }
}
