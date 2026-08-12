<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Quote;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        if (Quote::count() > 0) {
            return;
        }

        $customer1 = Customer::first();
        $stock1 = Stock::first();
        $stock2 = Stock::skip(1)->first();

        Quote::create([
            'quote_number' => 'QT-2026-1001',
            'customer_id' => $customer1?->id,
            'stock_id' => $stock1?->id,
            'company_name' => 'Phnom Penh Commercial Logistics Ltd',
            'contact_name' => 'Sokha Chan',
            'email' => 'sokha.chan@pplogistics.com.kh',
            'phone' => '+855 12 888 999',
            'product_name' => $stock1 ? $stock1->product_name : 'Heavy-Duty Industrial Pallet Racking System',
            'quantity' => 150,
            'target_price' => 280.00,
            'offered_price' => 265.00,
            'required_date' => now()->addDays(15),
            'status' => 'quoted',
            'message' => 'We require 150 units for our new distribution center in Sen Sok. Requesting volume discount and delivery schedule.',
            'admin_notes' => 'Supplier discount approved. Offered $265/unit for 150+ units.',
            'quoted_at' => now()->subHours(5),
        ]);

        Quote::create([
            'quote_number' => 'QT-2026-1002',
            'customer_id' => null,
            'stock_id' => $stock2?->id,
            'company_name' => 'Mekong Construction & Trading Co.',
            'contact_name' => 'Vannak Heng',
            'email' => 'v.heng@mekongtrading.com',
            'phone' => '+855 92 555 444',
            'product_name' => $stock2 ? $stock2->product_name : 'Commercial High-Power Diesel Generator 100kVA',
            'quantity' => 25,
            'target_price' => 4500.00,
            'offered_price' => null,
            'required_date' => now()->addDays(30),
            'status' => 'pending',
            'message' => 'Looking for bulk procurement of 25 generators for hotel development project in Siem Reap.',
            'admin_notes' => 'Checking warehouse stock availability with freight manager.',
            'quoted_at' => null,
        ]);

        Quote::create([
            'quote_number' => 'QT-2026-1003',
            'customer_id' => $customer1?->id,
            'stock_id' => null,
            'company_name' => 'Angkor Industrial Supplies',
            'contact_name' => 'Bora Sovann',
            'email' => 'bora@angkorindustrial.com',
            'phone' => '+855 77 111 222',
            'product_name' => 'Automated Forklift Electric Stacker 2.5 Ton',
            'quantity' => 10,
            'target_price' => 6800.00,
            'offered_price' => 6500.00,
            'required_date' => now()->addDays(10),
            'status' => 'approved',
            'message' => 'Need 10 electric stackers with 2-year warranty package.',
            'admin_notes' => 'Client accepted quote offer. Pending final purchase order for conversion.',
            'quoted_at' => now()->subDays(1),
        ]);
    }
}
