<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP-1001',
                'name' => 'John Anderson',
                'company_name' => 'Apex Global Logistics & Components',
                'email' => 'sales@apexglobal.com',
                'phone' => '+1 (555) 234-5678',
                'website' => 'https://apexglobal.com',
                'tax_id' => 'US-TAX-884920',
                'category' => 'Electronics & Hardware',
                'address' => '742 Industrial Tech Parkway, Suite 400',
                'city' => 'San Jose, CA',
                'country' => 'United States',
                'payment_terms' => 'Net 30',
                'rating' => 5,
                'status' => 'active',
                'notes' => 'Primary tier 1 supplier for microcontrollers and power distribution units.',
            ],
            [
                'code' => 'SUP-1002',
                'name' => 'Elena Rostova',
                'company_name' => 'Pacific Rim Raw Materials Co.',
                'email' => 'orders@pacificrimmaterials.com',
                'phone' => '+1 (555) 876-5432',
                'website' => 'https://pacificrimmaterials.com',
                'tax_id' => 'US-TAX-992103',
                'category' => 'Raw Materials',
                'address' => '12 Maritime Commerce Way',
                'city' => 'Seattle, WA',
                'country' => 'United States',
                'payment_terms' => 'Net 60',
                'rating' => 4,
                'status' => 'active',
                'notes' => 'Bulk aluminum alloy and specialized polymer resin distributor.',
            ],
            [
                'code' => 'SUP-1003',
                'name' => 'Marcus Vance',
                'company_name' => 'Nexus Industrial Supplies Ltd.',
                'email' => 'contact@nexusindustrial.com',
                'phone' => '+1 (555) 432-1098',
                'website' => 'https://nexusindustrial.com',
                'tax_id' => 'US-TAX-102938',
                'category' => 'Packaging & Freight',
                'address' => '88 Logistics Blvd, Dock 14',
                'city' => 'Chicago, IL',
                'country' => 'United States',
                'payment_terms' => 'Net 15',
                'rating' => 5,
                'status' => 'active',
                'notes' => 'Custom corrugated boxes, pallets, and shrink wrap supply.',
            ],
            [
                'code' => 'SUP-1004',
                'name' => 'Sarah Jenkins',
                'company_name' => 'Vanguard Power & Machinery',
                'email' => 'supplies@vanguardpower.com',
                'phone' => '+1 (555) 678-9012',
                'website' => 'https://vanguardpower.com',
                'tax_id' => 'US-TAX-554433',
                'category' => 'Machinery & Tools',
                'address' => '500 Heavy Industry Road',
                'city' => 'Detroit, MI',
                'country' => 'United States',
                'payment_terms' => 'Cash on Delivery',
                'rating' => 3,
                'status' => 'pending',
                'notes' => 'Undergoing annual compliance and quality verification audit.',
            ],
            [
                'code' => 'SUP-1005',
                'name' => 'Hiroshi Tanaka',
                'company_name' => 'Precision Optic Solutions',
                'email' => 'h.tanaka@precisionoptics.co.jp',
                'phone' => '+81 3 5555 0192',
                'website' => 'https://precisionoptics.jp',
                'tax_id' => 'JP-TAX-771122',
                'category' => 'Precision Instruments',
                'address' => '4-10-1 Chiyoda-ku',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'payment_terms' => 'Advance',
                'rating' => 5,
                'status' => 'active',
                'notes' => 'High precision optical sensors and laser alignment modules.',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['code' => $supplier['code']],
                $supplier
            );
        }
    }
}
