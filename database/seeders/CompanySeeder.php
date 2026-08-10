<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'company_code' => 'COMP-1001',
                'name' => 'Apex Mega Tech Distribution Inc.',
                'tax_id' => 'US-TX-9982341',
                'industry' => 'Electronics & Hardware Wholesale',
                'email' => 'corporate@apexmegatech.com',
                'phone' => '+1 (555) 234-8000',
                'website' => 'https://apexmegatech.com',
                'address' => '450 Industrial Parkway, Tech District',
                'city' => 'Austin',
                'country' => 'United States',
                'total_employees' => 250,
                'credit_limit' => 250000.00,
                'status' => 'active',
                'notes' => 'Major regional wholesale distributor for computer parts.',
            ],
            [
                'company_code' => 'COMP-1002',
                'name' => 'Global Systems & Logistics Corp',
                'tax_id' => 'US-CA-7712390',
                'industry' => 'IT Infrastructure Logistics',
                'email' => 'contact@globalsystems.io',
                'phone' => '+1 (555) 876-1100',
                'website' => 'https://globalsystems.io',
                'address' => '88 Silicon Enterprise Way',
                'city' => 'San Jose',
                'country' => 'United States',
                'total_employees' => 120,
                'credit_limit' => 150000.00,
                'status' => 'active',
                'notes' => 'Bulk client purchasing server motherboards and RAM.',
            ],
            [
                'company_code' => 'COMP-1003',
                'name' => 'Metro Retail Outlets Group',
                'tax_id' => 'US-FL-3398102',
                'industry' => 'Retail Chain Stores',
                'email' => 'procurement@metroretailgroup.com',
                'phone' => '+1 (555) 678-4000',
                'website' => 'https://metroretailgroup.com',
                'address' => '905 Ocean Commerce Blvd',
                'city' => 'Miami',
                'country' => 'United States',
                'total_employees' => 500,
                'credit_limit' => 100000.00,
                'status' => 'active',
                'notes' => 'Chain of 15 consumer electronics stores.',
            ],
            [
                'company_code' => 'COMP-1004',
                'name' => 'EuroTech Bulk Reseller Ltd.',
                'tax_id' => 'GB-VAT-9018231',
                'industry' => 'International B2B Trade',
                'email' => 'sales@eurotechbulk.eu',
                'phone' => '+44 20 7946 0900',
                'website' => 'https://eurotechbulk.eu',
                'address' => '14 Docklands Logistics Hub',
                'city' => 'London',
                'country' => 'United Kingdom',
                'total_employees' => 85,
                'credit_limit' => 180000.00,
                'status' => 'active',
                'notes' => 'European cross-border wholesale partner.',
            ],
            [
                'company_code' => 'COMP-1005',
                'name' => 'Nippon Hardware Supply Corp',
                'tax_id' => 'JP-TRN-1029384',
                'industry' => 'Semiconductors & Component Imports',
                'email' => 'info@nipponhardware.jp',
                'phone' => '+81 3 5555 0100',
                'website' => 'https://nipponhardware.jp',
                'address' => '2-11 Akihabara Tech Trade Tower',
                'city' => 'Tokyo',
                'country' => 'Japan',
                'total_employees' => 300,
                'credit_limit' => 300000.00,
                'status' => 'active',
                'notes' => 'Strategic Asia distributor.',
            ],
            [
                'company_code' => 'COMP-1006',
                'name' => 'Vance Computer Solutions LLC',
                'tax_id' => 'US-NY-4482910',
                'industry' => 'System Integration & Enterprise IT',
                'email' => 'admin@vancecomputers.com',
                'phone' => '+1 (555) 432-5000',
                'website' => 'https://vancecomputers.com',
                'address' => '120 Broadway Retail Mall, Suite 400',
                'city' => 'New York',
                'country' => 'United States',
                'total_employees' => 45,
                'credit_limit' => 50000.00,
                'status' => 'pending',
                'notes' => 'New account pending corporate credit line approval.',
            ],
        ];

        foreach ($companies as $data) {
            Company::updateOrCreate(
                ['company_code' => $data['company_code']],
                $data
            );
        }
    }
}
