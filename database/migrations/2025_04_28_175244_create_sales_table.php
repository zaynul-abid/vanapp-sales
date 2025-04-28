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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_master_id')->nullable()->constrained('sale_masters')->nullOnDelete();

            $table->string('bill_no')->unique();
            $table->date('sale_date');
            $table->time('sale_time');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained('customers')
                ->nullOnDelete();

            $table->foreignId('item_id')
                ->nullable()
                ->constrained('item_unit_details')
                ->nullOnDelete();
            $table->string('item_name');


            $table->decimal('rate', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 10, 2);

            $table->string('unit');

            $table->decimal('gross_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);


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
        Schema::dropIfExists('sales');
    }
};
