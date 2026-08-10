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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('sku');
            $table->string('product_name');
            $table->string('category')->default('General');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('min_reorder_level')->default(10);
            $table->integer('max_capacity')->default(1000);
            $table->decimal('unit_cost', 12, 2)->default(0.00);
            $table->string('rack_location')->nullable();
            $table->string('status')->default('in_stock'); // in_stock, low_stock, out_of_stock, overstocked
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
