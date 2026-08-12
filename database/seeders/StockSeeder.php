<?php

namespace Database\Seeders;

use App\Data\FrontendData;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phnWarehouse = Warehouse::where('code', 'WH-PHN-001')->first() ?? Warehouse::first();
        $repWarehouse = Warehouse::where('code', 'WH-REP-002')->first() ?? $phnWarehouse;
        $kasWarehouse = Warehouse::where('code', 'WH-KAS-003')->first() ?? $phnWarehouse;

        if (!$phnWarehouse) {
            return;
        }

        $warehouses = [$phnWarehouse, $repWarehouse, $kasWarehouse];

        // 1. Seed products from FrontendData::products()
        $products = FrontendData::products();
        foreach ($products as $index => $product) {
            $warehouse = $warehouses[$index % count($warehouses)];

            $tierPrices = array_map(function ($tier) {
                return [
                    'min_qty' => $tier['minQty'] ?? 1,
                    'max_qty' => $tier['maxQty'] ?? null,
                    'price' => (float) $tier['price'],
                ];
            }, $product['wholesalePrices'] ?? []);

            $qty = (int) ($product['stock'] ?? 100);
            $reserved = min($qty, rand(2, 15));
            $unitCost = round($product['price'] * 0.75, 2);
            $moq = (int) ($product['moq'] ?? 5);

            $status = 'in_stock';
            if ($qty <= 0) {
                $status = 'out_of_stock';
            } elseif ($qty <= $moq * 2) {
                $status = 'low_stock';
            }

            Stock::updateOrCreate(
                [
                    'sku' => $product['sku'],
                ],
                [
                    'warehouse_id' => $warehouse->id,
                    'sku' => $product['sku'],
                    'product_name' => $product['name'],
                    'image' => $product['image'] ?? null,
                    'short_description' => $product['description'] ?? null,
                    'description' => $product['description'] ?? null,
                    'details' => $product['specifications'] ?? [],
                    'category' => $product['category'] ?? 'General',
                    'quantity' => $qty,
                    'reserved_quantity' => $reserved,
                    'min_reorder_level' => max(5, $moq * 2),
                    'max_capacity' => max(500, $qty * 3),
                    'unit_cost' => $unitCost,
                    'retail_price' => (float) $product['price'],
                    'tier_prices' => $tierPrices,
                    'rack_location' => 'RACK-' . sprintf('%02d', ($index % 20) + 1),
                    'status' => $status,
                    'notes' => 'Seeded from catalog: ' . ($product['brand'] ?? 'Hardware') . ' (' . ($product['warranty'] ?? 'Standard') . ')',
                ]
            );
        }

        // 2. Sample extra warehouse stocks
        $extraStocks = [
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
                'retail_price' => 520.00,
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
                'retail_price' => 95.00,
                'rack_location' => 'B-04-02',
                'status' => 'low_stock',
                'notes' => 'Stock falling near safety reorder threshold.',
            ],
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
                'retail_price' => 28.00,
                'rack_location' => 'R-01-05',
                'status' => 'in_stock',
                'notes' => 'Standard wholesale packaging stock.',
            ],
        ];

        foreach ($extraStocks as $item) {
            Stock::updateOrCreate(
                [
                    'sku' => $item['sku'],
                ],
                $item
            );
        }
    }
}
