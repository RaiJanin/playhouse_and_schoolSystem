<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table for parent_info and phone_numbers
     */
    public function up(): void
    {
        Schema::create('parent_phone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_info_id')->constrained('parent_info')->onDelete('cascade');
            $table->foreignId('phone_number_id')->constrained('phone_numbers')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_phone');
    }
};
