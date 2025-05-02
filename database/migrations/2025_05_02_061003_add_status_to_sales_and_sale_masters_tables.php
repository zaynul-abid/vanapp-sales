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
            $table->boolean('status')->default(true)->after('user_id'); // you can change 'after' to suit your column order
        });

        Schema::table('sale_masters', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('sale_masters', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
