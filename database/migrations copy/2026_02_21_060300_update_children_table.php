<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add parent_info_id and guardian_id foreign keys to children table
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            // Add parent_info_id column (nullable for backward compatibility)
            $table->foreignId('parent_info_id')
                  ->nullable()
                  ->constrained('parent_info')
                  ->onDelete('cascade')
                  ->after('customer_record_id');

            // Add guardian_id column (nullable)
            $table->foreignId('guardian_id')
                  ->nullable()
                  ->constrained('guardians')
                  ->onDelete('cascade')
                  ->after('parent_info_id');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropForeign(['parent_info_id']);
            $table->dropForeign(['guardian_id']);
            $table->dropColumn(['parent_info_id', 'guardian_id']);
        });
    }
};
