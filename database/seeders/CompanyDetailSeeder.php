<?php

namespace Database\Seeders;

use App\Models\CompanyDetail;
use Illuminate\Database\Seeder;

class CompanyDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyDetail::firstOrCreate(
            ['id' => 1],
            [
                'company_name' => 'B2B Wholesale Enterprise Global',
                'legal_name' => 'B2B Wholesale Systems & Trade Inc.',
                'tax_number' => 'VAT-US-991823410',
                'registration_number' => 'REG-2026-90184',
                'email' => 'sales@b2bwholesale.com',
                'phone' => '+1 (800) 555-8800',
                'support_email' => 'support@b2bwholesale.com',
                'website' => 'https://b2bwholesale.com',
                'address' => '100 Enterprise Logistics Way, Suite 500',
                'city' => 'Austin',
                'state' => 'Texas',
                'postal_code' => '78701',
                'country' => 'United States',
                'bank_name' => 'JPMorgan Chase Bank, N.A.',
                'account_name' => 'B2B Wholesale Systems Corp',
                'account_number' => '449102839102',
                'swift_code' => 'CHASUS33XXX',
                'iban' => 'US89CHAS449102839102',
                'currency' => 'USD ($)',
                'timezone' => 'America/Chicago',
                'description' => 'Global wholesale B2B platform supplying computer hardware, electronics, server equipment, and components to enterprise clients.',
            ]
        );
    }
}
