<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phnWarehouse = Warehouse::where('code', 'WH-PHN-001')->first();
        $repWarehouse = Warehouse::where('code', 'WH-REP-002')->first();
        $kasWarehouse = Warehouse::where('code', 'WH-KAS-003')->first();

        if (!$phnWarehouse || !$repWarehouse || !$kasWarehouse) {
            return;
        }

        $stocks = [
            // Phnom Penh Hub
            [
                'warehouse_id' => $phnWarehouse->id,
                'sku' => 'SKU-ELEC-1001',
                'product_name' => 'Industrial Solar Inverter 5kW',
                'category' => 'Electronics & Energy',
                'quantity' => 450,
                'reserved_quantity' => 50,
                'min_reorder_level' => 100,
                'max_capacity' => 1000,
                'unit_cost' => 380.00,
                'rack_location' => 'A-01-12',
                'status' => 'in_stock',
                'notes' => 'High demand commercial component.',
            ],
            [
                'warehouse_id' => $phnWarehouse->id,
                'sku' => 'SKU-FMCG-2004',
                'product_name' => 'Premium Wholesale Organic Coffee Beans (10kg Bag)',
                'category' => 'Food & Beverage',
                'quantity' => 85,
                'reserved_quantity' => 15,
                'min_reorder_level' => 150,
                'max_capacity' => 2000,
                'unit_cost' => 65.50,
                'rack_location' => 'B-04-02',
                'status' => 'low_stock',
                'notes' => 'Stock falling near safety reorder threshold.',
            ],
            [
                'warehouse_id' => $phnWarehouse->id,
                'sku' => 'SKU-TEXT-3010',
                'product_name' => 'Heavy Duty Cotton Fabric Rolls (100m)',
                'category' => 'Textiles & Raw Materials',
                'quantity' => 0,
                'reserved_quantity' => 0,
                'min_reorder_level' => 50,
                'max_capacity' => 500,
                'unit_cost' => 120.00,
                'rack_location' => 'C-02-08',
                'status' => 'out_of_stock',
                'notes' => 'Urgent replenishment order placed with supplier.',
            ],

            // Siem Reap Regional Depot
            [
                'warehouse_id' => $repWarehouse->id,
                'sku' => 'SKU-PACK-4050',
                'product_name' => 'Corrugated Shipping Boxes XL (Pack of 50)',
                'category' => 'Packaging Materials',
                'quantity' => 1200,
                'reserved_quantity' => 100,
                'min_reorder_level' => 300,
                'max_capacity' => 1500,
                'unit_cost' => 18.50,
                'rack_location' => 'R-01-05',
                'status' => 'in_stock',
                'notes' => 'Standard wholesale packaging stock.',
            ],
            [
                'warehouse_id' => $repWarehouse->id,
                'sku' => 'SKU-FMCG-2004',
                'product_name' => 'Premium Wholesale Organic Coffee Beans (10kg Bag)',
                'category' => 'Food & Beverage',
                'quantity' => 320,
                'reserved_quantity' => 20,
                'min_reorder_level' => 100,
                'max_capacity' => 800,
                'unit_cost' => 65.50,
                'rack_location' => 'R-03-01',
                'status' => 'in_stock',
                'notes' => 'Healthy regional stock.',
            ],

            // Cold Storage Sihanoukville
            [
                'warehouse_id' => $kasWarehouse->id,
                'sku' => 'SKU-COLD-9001',
                'product_name' => 'Frozen Seafood Grade A (Master Case 25kg)',
                'category' => 'Cold & Frozen Goods',
                'quantity' => 680,
                'reserved_quantity' => 80,
                'min_reorder_level' => 200,
                'max_capacity' => 1000,
                'unit_cost' => 210.00,
                'rack_location' => 'FREEZER-02',
                'status' => 'in_stock',
                'notes' => 'Requires temperature monitoring at -18C.',
            ],
        ];

        foreach ($stocks as $item) {
            Stock::updateOrCreate(
                [
                    'warehouse_id' => $item['warehouse_id'],
                    'sku' => $item['sku'],
                ],
                $item
            );
        }
    }
}
