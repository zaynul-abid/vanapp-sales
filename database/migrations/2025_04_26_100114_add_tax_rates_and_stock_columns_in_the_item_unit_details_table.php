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
        Schema::table('item_unit_details', function (Blueprint $table) {
            $table->decimal('tax_percentage', 5, 2)->nullable()->after('quantity');
            $table->decimal('wholesale_price', 10, 2)->nullable()->after('tax_percentage');
            $table->decimal('retail_price', 10, 2)->nullable()->after('wholesale_price');
            $table->decimal('stock', 8, 2)->nullable()->after('retail_price');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_unit_details', function (Blueprint $table) {
            $table->dropColumn('tax_percentage');
            $table->dropColumn('wholesale_price');
            $table->dropColumn('retail_price');
            $table->dropColumn('stock');
            $table->dropSoftDeletes();
        });
    }
};
