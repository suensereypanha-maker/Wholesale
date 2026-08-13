<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Database\Seeder;

class SupplierPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();

        if ($suppliers->isEmpty()) {
            $this->call(SupplierSeeder::class);
            $suppliers = Supplier::all();
        }

        $sup1 = $suppliers->firstWhere('code', 'SUP-1001') ?? $suppliers->first();
        $sup2 = $suppliers->firstWhere('code', 'SUP-1002') ?? $suppliers->skip(1)->first() ?? $sup1;
        $sup3 = $suppliers->firstWhere('code', 'SUP-1003') ?? $suppliers->skip(2)->first() ?? $sup1;
        $sup4 = $suppliers->firstWhere('code', 'SUP-1004') ?? $suppliers->skip(3)->first() ?? $sup1;
        $sup5 = $suppliers->firstWhere('code', 'SUP-1005') ?? $suppliers->skip(4)->first() ?? $sup1;

        $payments = [
            [
                'payment_code'   => 'PAY-2026-0001',
                'supplier_id'    => $sup1->id,
                'invoice_number' => 'INV-APEX-9921',
                'purchase_date'  => now()->subDays(20)->toDateString(),
                'due_date'       => now()->addDays(10)->toDateString(),
                'total_amount'   => 12500.00,
                'paid_amount'    => 12500.00,
                'due_amount'     => 0.00,
                'payment_status' => 'paid',
                'payment_method' => 'Bank Transfer',
                'payment_date'   => now()->subDays(2)->toDateString(),
                'notes'          => "Full payment transferred via Wire Ref #TRX-998201.",
            ],
            [
                'payment_code'   => 'PAY-2026-0002',
                'supplier_id'    => $sup2->id,
                'invoice_number' => 'INV-PAC-4410',
                'purchase_date'  => now()->subDays(15)->toDateString(),
                'due_date'       => now()->addDays(15)->toDateString(),
                'total_amount'   => 8400.00,
                'paid_amount'    => 0.00,
                'due_amount'     => 8400.00,
                'payment_status' => 'unpaid',
                'payment_method' => 'Wire Transfer',
                'payment_date'   => null,
                'notes'          => "Payment scheduled for end of month per Net 60 agreement.",
            ],
            [
                'payment_code'   => 'PAY-2026-0003',
                'supplier_id'    => $sup3->id,
                'invoice_number' => 'INV-NEX-1092',
                'purchase_date'  => now()->subDays(25)->toDateString(),
                'due_date'       => now()->subDays(5)->toDateString(), // Overdue
                'total_amount'   => 3200.00,
                'paid_amount'    => 1000.00,
                'due_amount'     => 2200.00,
                'payment_status' => 'partial',
                'payment_method' => 'Cash',
                'payment_date'   => now()->subDays(10)->toDateString(),
                'notes'          => "Deposit paid $1,000. Remaining balance $2,200 past due.",
            ],
            [
                'payment_code'   => 'PAY-2026-0004',
                'supplier_id'    => $sup4->id,
                'invoice_number' => 'INV-VANG-7781',
                'purchase_date'  => now()->subDays(35)->toDateString(),
                'due_date'       => now()->subDays(5)->toDateString(), // Overdue
                'total_amount'   => 15600.00,
                'paid_amount'    => 0.00,
                'due_amount'     => 15600.00,
                'payment_status' => 'unpaid',
                'payment_method' => 'Credit Line',
                'payment_date'   => null,
                'notes'          => "Pending audit approval before final payment disbursement.",
            ],
            [
                'payment_code'   => 'PAY-2026-0005',
                'supplier_id'    => $sup5->id,
                'invoice_number' => 'INV-OPT-3301',
                'purchase_date'  => now()->subDays(5)->toDateString(),
                'due_date'       => now()->addDays(25)->toDateString(),
                'total_amount'   => 21000.00,
                'paid_amount'    => 21000.00,
                'due_amount'     => 0.00,
                'payment_status' => 'paid',
                'payment_method' => 'Bank Transfer',
                'payment_date'   => now()->subDays(3)->toDateString(),
                'notes'          => "Advance payment completed for optical sensor order.",
            ],
            [
                'payment_code'   => 'PAY-2026-0006',
                'supplier_id'    => $sup1->id,
                'invoice_number' => 'INV-APEX-9988',
                'purchase_date'  => now()->subDays(2)->toDateString(),
                'due_date'       => now()->addDays(28)->toDateString(),
                'total_amount'   => 5400.00,
                'paid_amount'    => 2000.00,
                'due_amount'     => 3400.00,
                'payment_status' => 'partial',
                'payment_method' => 'Bank Transfer',
                'payment_date'   => now()->subDays(1)->toDateString(),
                'notes'          => "50% deposit paid. Remaining 50% due on delivery.",
            ],
        ];

        foreach ($payments as $payment) {
            SupplierPayment::updateOrCreate(
                ['payment_code' => $payment['payment_code']],
                $payment
            );
        }
    }
}
