<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('tier')->default('Standard Wholesale'); // e.g. VIP Platinum, Wholesale Gold, Bulk Silver, Standard Wholesale
            $table->decimal('wholesale_discount', 5, 2)->default(0.00); // percentage discount e.g. 15.00%
            $table->decimal('credit_limit', 12, 2)->default(0.00); // e.g. $50,000.00
            $table->decimal('total_spent', 12, 2)->default(0.00); // total lifetime wholesale spending
            $table->integer('total_orders')->default(0);
            $table->string('payment_terms')->default('Net 30'); // Net 15, Net 30, Net 60, COD, Prepaid
            $table->string('tax_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('status')->default('active'); // active, pending, inactive
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
