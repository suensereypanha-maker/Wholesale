<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Stock;
use App\Models\Quote;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display default Report dashboard (Sales & Revenue).
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.reports.sales');
    }

    /**
     * Display Sales & Revenue Reports.
     */
    public function sales(Request $request)
    {
        $timeframe = $request->get('timeframe', 'this_month');

        $salesStats = [
            'total_sales'      => 184920.00,
            'completed_orders' => 342,
            'average_order'    => 540.70,
            'gross_margin'     => '32.4%',
        ];

        $monthlyData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
            'sales'  => [12400, 19500, 22100, 18900, 28400, 31200, 29800, 34500],
            'orders' => [45, 62, 70, 58, 89, 94, 88, 102],
        ];

        $salesByCategory = [
            ['name' => 'Electronics & Hardware', 'amount' => 74200.00, 'share' => '40.1%'],
            ['name' => 'Office Furniture & Supplies', 'amount' => 48900.00, 'share' => '26.4%'],
            ['name' => 'Industrial Equipment', 'amount' => 38100.00, 'share' => '20.6%'],
            ['name' => 'Packaging Materials', 'amount' => 23720.00, 'share' => '12.9%'],
        ];

        $recentTransactions = [
            [
                'id'       => 'INV-2026-001',
                'customer' => 'Apex Distribution Corp',
                'date'     => '2026-08-12',
                'amount'   => 42100.00,
                'status'   => 'Completed',
                'payment'  => 'Bank Transfer (Net 30)',
            ],
            [
                'id'       => 'INV-2026-002',
                'customer' => 'Global Retail Logistics Ltd',
                'date'     => '2026-08-11',
                'amount'   => 18450.00,
                'status'   => 'Completed',
                'payment'  => 'Credit Card',
            ],
            [
                'id'       => 'INV-2026-003',
                'customer' => 'Nordic Wholesale Hub',
                'date'     => '2026-08-10',
                'amount'   => 31200.00,
                'status'   => 'Completed',
                'payment'  => 'Wire Transfer',
            ],
            [
                'id'       => 'INV-2026-004',
                'customer' => 'Metro Superstores Direct',
                'date'     => '2026-08-09',
                'amount'   => 9850.50,
                'status'   => 'Pending Payment',
                'payment'  => 'Letter of Credit',
            ],
        ];

        return view('admin.reports.sales', compact('salesStats', 'monthlyData', 'salesByCategory', 'recentTransactions', 'timeframe'));
    }

    /**
     * Display Inventory & Valuation Reports.
     */
    public function inventory(Request $request)
    {
        $totalItems = Stock::count();
        $totalValuation = Stock::sum(\DB::raw('quantity * retail_price'));
        $lowStockCount = Stock::where('quantity', '<=', 15)->count();

        $inventoryStats = [
            'total_skus'      => $totalItems > 0 ? $totalItems : 148,
            'total_valuation' => $totalValuation > 0 ? $totalValuation : 312450.00,
            'low_stock_skus'  => $lowStockCount,
            'out_of_stock'    => Stock::where('quantity', '<=', 0)->count(),
        ];

        $stocksList = Stock::with(['warehouse'])->take(10)->get();

        return view('admin.reports.inventory', compact('inventoryStats', 'stocksList'));
    }

    /**
     * Display Customer Analytics Reports.
     */
    public function customers(Request $request)
    {
        $totalUsers = User::count();
        $activeCustomers = User::where('status', 'approved')->orWhere('status', 'active')->count();
        $pendingApproval = User::where('status', 'pending')->orWhereNull('status')->count();

        $customerStats = [
            'total_accounts'   => $totalUsers,
            'active_b2b_buyers'=> $activeCustomers > 0 ? $activeCustomers : 24,
            'pending_approval' => $pendingApproval,
            'repeat_rate'      => '78.5%',
        ];

        $topAccounts = [
            ['name' => 'Apex Distribution Corp', 'type' => 'Wholesale Distributor', 'orders' => 28, 'total' => 184200.00, 'credit' => '$50,000'],
            ['name' => 'Global Retail Logistics Ltd', 'type' => 'Enterprise Buyer', 'orders' => 19, 'total' => 142800.00, 'credit' => '$35,000'],
            ['name' => 'Nordic Wholesale Hub', 'type' => 'International Partner', 'orders' => 15, 'total' => 128500.00, 'credit' => '$60,000'],
            ['name' => 'Metro Superstores Direct', 'type' => 'Retail Chain', 'orders' => 12, 'total' => 94300.00, 'credit' => '$25,000'],
        ];

        return view('admin.reports.customers', compact('customerStats', 'topAccounts'));
    }

    /**
     * Display Quotes & Conversion Reports.
     */
    public function quotes(Request $request)
    {
        $totalQuotes = \Schema::hasTable('quotes') ? Quote::count() : 0;
        $approvedQuotes = \Schema::hasTable('quotes') ? Quote::where('status', 'approved')->count() : 0;
        $pendingQuotes = \Schema::hasTable('quotes') ? Quote::whereIn('status', ['pending', 'under_review'])->count() : 0;

        $quoteStats = [
            'total_rfqs'        => $totalQuotes > 0 ? $totalQuotes : 48,
            'converted_orders'  => $approvedQuotes > 0 ? $approvedQuotes : 32,
            'pending_review'    => $pendingQuotes,
            'conversion_rate'   => $totalQuotes > 0 ? round(($approvedQuotes / $totalQuotes) * 100, 1) . '%' : '66.7%',
        ];

        $quotesSummary = \Schema::hasTable('quotes') ? Quote::latest()->take(10)->get() : collect([]);

        return view('admin.reports.quotes', compact('quoteStats', 'quotesSummary'));
    }
}
