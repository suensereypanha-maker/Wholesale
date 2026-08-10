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
        Schema::table('stocks', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('product_name');
            $table->text('description')->nullable()->after('short_description');
            $table->json('details')->nullable()->after('description');
            $table->decimal('retail_price', 12, 2)->default(0.00)->after('unit_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'description', 'details', 'retail_price']);
        });
    }
};
