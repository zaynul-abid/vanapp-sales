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
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('retail_price', 8, 2)->after('wholesale_price')->nullable();
            $table->decimal('opening_stock', 8, 2)->after('retail_price')->nullable();
            $table->decimal('current_stock', 8, 2)->after('opening_stock');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('retail_price');
            $table->dropColumn('opening_stock');
            $table->dropColumn('current_stock');
        });
    }
};
