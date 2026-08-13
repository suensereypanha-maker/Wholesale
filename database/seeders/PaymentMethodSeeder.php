<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'code'           => 'PM-001',
                'name'           => 'Bank Transfer',
                'type'           => 'bank',
                'account_number' => '000 123 456 789 (ABA Bank)',
                'account_name'   => 'B2B Wholesale Hub Co., Ltd.',
                'status'         => 'active',
                'notes'          => 'Primary corporate bank account for telegraphic wire and local transfers.',
            ],
            [
                'code'           => 'PM-002',
                'name'           => 'ABA Pay / KHQR',
                'type'           => 'digital',
                'account_number' => '000 987 654 321',
                'account_name'   => 'B2B Wholesale Hub',
                'status'         => 'active',
                'notes'          => 'Instant digital QR code payment channel.',
            ],
            [
                'code'           => 'PM-003',
                'name'           => 'Cash',
                'type'           => 'cash',
                'account_number' => null,
                'account_name'   => 'Over-the-Counter Cash',
                'status'         => 'active',
                'notes'          => 'Direct cash transaction at warehouse or office counter.',
            ],
            [
                'code'           => 'PM-004',
                'name'           => 'Wire Transfer',
                'type'           => 'bank',
                'account_number' => 'SWIFT: ABACPP21 / IBAN-US-99120',
                'account_name'   => 'B2B Wholesale Hub International',
                'status'         => 'active',
                'notes'          => 'International cross-border SWIFT wire transfers.',
            ],
            [
                'code'           => 'PM-005',
                'name'           => 'Credit Line',
                'type'           => 'credit',
                'account_number' => 'CREDIT-LINE-NET30',
                'account_name'   => 'Approved Supplier Terms',
                'status'         => 'active',
                'notes'          => 'Standard Net 30 / Net 60 revolving supplier credit line.',
            ],
            [
                'code'           => 'PM-006',
                'name'           => 'Cheque',
                'type'           => 'other',
                'account_number' => 'CHK-SERIES-8800',
                'account_name'   => 'Corporate Certified Cheque',
                'status'         => 'active',
                'notes'          => 'Physical bank cheque payable to supplier company name.',
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
