<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $stocks = Stock::all();

        if ($customers->isEmpty()) {
            return;
        }

        $sampleOrders = [
            [
                'order_number' => 'ORD-2026-1001',
                'customer_code' => 'CUST-1001',
                'status' => 'delivered',
                'payment_status' => 'paid',
                'payment_terms' => 'Net 60',
                'notes' => 'Bulk palletized shipment to Austin distribution center.',
                'items' => [
                    ['name' => 'Industrial Solar Inverter 5kW', 'qty' => 10, 'price' => 450.00],
                    ['name' => 'Premium Wholesale Organic Coffee Beans (10kg Bag)', 'qty' => 50, 'price' => 85.00],
                ],
            ],
            [
                'order_number' => 'ORD-2026-1002',
                'customer_code' => 'CUST-1002',
                'status' => 'shipped',
                'payment_status' => 'partially_paid',
                'payment_terms' => 'Net 30',
                'notes' => 'Express air freight dispatch for San Jose hub.',
                'items' => [
                    ['name' => 'Industrial Solar Inverter 5kW', 'qty' => 20, 'price' => 440.00],
                ],
            ],
            [
                'order_number' => 'ORD-2026-1003',
                'customer_code' => 'CUST-1003',
                'status' => 'processing',
                'payment_status' => 'unpaid',
                'payment_terms' => 'Net 30',
                'notes' => 'Weekly retail chain replenishment batch.',
                'items' => [
                    ['name' => 'Heavy Duty Cotton Fabric Rolls (100m)', 'qty' => 35, 'price' => 120.00],
                    ['name' => 'Premium Wholesale Organic Coffee Beans (10kg Bag)', 'qty' => 25, 'price' => 85.00],
                ],
            ],
            [
                'order_number' => 'ORD-2026-1004',
                'customer_code' => 'CUST-1004',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_terms' => 'Net 30',
                'notes' => 'International wholesale procurement inquiry order.',
                'items' => [
                    ['name' => 'Industrial Solar Inverter 5kW', 'qty' => 15, 'price' => 430.00],
                ],
            ],
        ];

        foreach ($sampleOrders as $orderData) {
            if (Order::where('order_number', $orderData['order_number'])->exists()) {
                continue;
            }

            $customer = $customers->firstWhere('customer_code', $orderData['customer_code']) ?? $customers->first();

            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($orderData['items'] as $itemData) {
                $stock = $stocks->firstWhere('product_name', $itemData['name']);
                $itemSubtotal = $itemData['qty'] * $itemData['price'];
                $subtotal += $itemSubtotal;

                $itemsToCreate[] = [
                    'stock_id' => $stock ? $stock->id : null,
                    'product_name' => $itemData['name'],
                    'quantity' => $itemData['qty'],
                    'unit_price' => $itemData['price'],
                    'subtotal' => $itemSubtotal,
                ];
            }

            // Calculate wholesale discount based on customer tier discount
            $discountPercent = $customer->wholesale_discount ?? 0;
            $discountAmount = round(($subtotal * $discountPercent) / 100, 2);
            $taxAmount = round(($subtotal - $discountAmount) * 0.05, 2); // 5% tax
            $totalAmount = round($subtotal - $discountAmount + $taxAmount, 2);

            $order = Order::create([
                'order_number' => $orderData['order_number'],
                'customer_id' => $customer->id,
                'order_source' => 'admin',
                'status' => $orderData['status'],
                'payment_status' => $orderData['payment_status'],
                'payment_terms' => $orderData['payment_terms'] ?? $customer->payment_terms,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'shipping_address' => $customer->address . ', ' . $customer->city . ', ' . $customer->country,
                'notes' => $orderData['notes'],
                'order_date' => now()->subDays(rand(1, 15)),
                'due_date' => now()->addDays(30),
            ]);

            foreach ($itemsToCreate as $item) {
                $order->items()->create($item);
            }
        }
    }
}
