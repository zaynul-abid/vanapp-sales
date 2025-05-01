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
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('unit_quantity', 10, 2)->after('unit');
            $table->decimal('custom_quantity', 10, 2)->after('unit_quantity');
            $table->decimal('total_quantity', 10, 2)->after('custom_quantity');
            $table->decimal('tax_percentage', 10, 2)->after('gross_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('unit_quantity');
            $table->dropColumn('custom_quantity');
            $table->dropColumn('total_quantity');
            $table->dropColumn('tax_percentage');

        });
    }
};
