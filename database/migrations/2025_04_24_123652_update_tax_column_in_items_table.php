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
            $table->dropColumn('tax_percentage');

            // Add new tax_id column
            $table->foreignId('tax_id')
                ->nullable()
                ->after('default_unit_id')
                ->constrained('taxes')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['tax_id']);
            $table->dropColumn('tax_id');

            $table->decimal('tax_percentage', 5, 2)->nullable();
        });
    }
};
