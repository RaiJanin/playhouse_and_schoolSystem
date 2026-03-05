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
        Schema::create('market', function (Blueprint $table) {
            $table->string('mkt_code', 50)->primary();
            $table->string('mkt_desc', 255);
            $table->string('branch', 100)->nullable();
            $table->string('transferred', 1)->default('N');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market');
    }
};
