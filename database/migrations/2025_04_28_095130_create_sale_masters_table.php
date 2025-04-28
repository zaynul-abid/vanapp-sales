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
        Schema::create('sale_masters', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->date('sale_date');
            $table->time('sale_time');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            $table->string('customer_name');
            $table->string('sale_type')->nullable();

            $table->decimal('gross_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2);

            $table->decimal('net_gross_amount', 10, 2);
            $table->decimal('net_tax_amount', 10, 2);
            $table->decimal('net_total_amount', 10, 2);

            $table->decimal('cash_amount', 10, 2);
            $table->decimal('credit_amount', 10, 2);
            $table->decimal('upi_amount', 10, 2);

            $table->text('narration')->nullable();
            $table->string('financial_year', 9)->nullable();

            $table->foreignId('van_id')
                ->nullable()
                ->constrained('vans')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_masters');
    }
};
