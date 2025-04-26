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
        Schema::table('vans', function (Blueprint $table) {
            $table->foreignId('employee_id')
                ->nullable()
                ->after('status')
                ->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vans', function (Blueprint $table) {
            // Correct way to drop foreign key
            $table->dropForeign(['employee_id']);

            // Also drop the column if you want complete rollback
            $table->dropColumn('employee_id');
        });
    }
};
