<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Make email nullable in parent_info table
     */
    public function up(): void
    {
        Schema::table('parent_info', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_info', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
