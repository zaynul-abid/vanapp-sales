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
        Schema::table('sale_masters', function (Blueprint $table) {
            $table->decimal('card_amount', 10, 2)->after('upi_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_masters', function (Blueprint $table) {
            $table->dropColumn('upi_amount');
        });
    }
};
