<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;

    protected $table = 'company_details';

    protected $fillable = [
        'company_name',
        'legal_name',
        'tax_number',
        'registration_number',
        'email',
        'phone',
        'support_email',
        'website',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'bank_name',
        'account_name',
        'account_number',
        'swift_code',
        'iban',
        'currency',
        'timezone',
        'description',
    ];
}
