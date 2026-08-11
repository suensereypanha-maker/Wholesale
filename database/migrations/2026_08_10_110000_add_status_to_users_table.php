<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('name');
            $table->string('tax_number')->nullable()->after('company');
            $table->string('phone')->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('province')->nullable()->after('city');
            $table->string('zip')->nullable()->after('province');
            $table->string('country')->nullable()->after('zip');
            $table->string('tier')->default('Standard Wholesale')->after('country');
            $table->decimal('credit_limit', 12, 2)->default(0.00)->after('tier');
            $table->decimal('wholesale_discount', 5, 2)->default(0.00)->after('credit_limit');
            $table->string('status')->default('active')->after('remember_token');
        });

        // Set existing users to active
        DB::table('users')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company',
                'tax_number',
                'phone',
                'address',
                'city',
                'province',
                'zip',
                'country',
                'tier',
                'credit_limit',
                'wholesale_discount',
                'status',
            ]);
        });
    }
};
