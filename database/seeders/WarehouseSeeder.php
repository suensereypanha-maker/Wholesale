<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'code' => 'WH-PHN-001',
                'name' => 'Central Phnom Penh Logistics Hub',
                'type' => 'Distribution Center',
                'location' => 'Phnom Penh Special Economic Zone, National Road 4, Cambodia',
                'contact_name' => 'Sokha Chan',
                'contact_email' => 'sokha.chan@b2bwholesale.com',
                'contact_phone' => '+855 23 888 101',
                'capacity' => 25000,
                'status' => 'active',
                'notes' => 'Primary fulfillment center for high-turnover FMCG and retail goods.',
            ],
            [
                'code' => 'WH-REP-002',
                'name' => 'Siem Reap Regional Depot',
                'type' => 'Regional Hub',
                'location' => 'Airport Road, Sangkat Kakab, Siem Reap',
                'contact_name' => 'Vannak Heng',
                'contact_email' => 'vannak.heng@b2bwholesale.com',
                'contact_phone' => '+855 63 963 202',
                'capacity' => 12000,
                'status' => 'active',
                'notes' => 'Regional supply facility serving Northern province wholesale buyers.',
            ],
            [
                'code' => 'WH-KAS-003',
                'name' => 'Sihanoukville Port Cold Storage',
                'type' => 'Cold Storage',
                'location' => 'Autonomous Port Zone, Sihanoukville',
                'contact_name' => 'Keo Pich',
                'contact_email' => 'keo.pich@b2bwholesale.com',
                'contact_phone' => '+855 34 934 303',
                'capacity' => 18000,
                'status' => 'active',
                'notes' => 'Temperature-controlled refrigerated warehouse for perishable imports.',
            ],
            [
                'code' => 'WH-BAT-004',
                'name' => 'Battambang Agricultural Warehouse',
                'type' => 'Bulk Depot',
                'location' => 'Industrial Zone, National Road 5, Battambang',
                'contact_name' => 'Borey Sovann',
                'contact_email' => 'borey.s@b2bwholesale.com',
                'contact_phone' => '+855 53 730 404',
                'capacity' => 15000,
                'status' => 'maintenance',
                'notes' => 'Currently under expansion for grain and dry bulk goods.',
            ],
        ];

        foreach ($warehouses as $data) {
            Warehouse::updateOrCreate(['code' => $data['code']], $data);
        }
    }
}
