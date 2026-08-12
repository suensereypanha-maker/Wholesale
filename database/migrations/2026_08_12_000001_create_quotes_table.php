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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->onDelete('set null');
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->string('product_name');
            $table->integer('quantity')->default(1);
            $table->decimal('target_price', 12, 2)->nullable();
            $table->decimal('offered_price', 12, 2)->nullable();
            $table->date('required_date')->nullable();
            $table->string('status', 30)->default('pending'); // pending, under_review, quoted, approved, rejected, converted
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
