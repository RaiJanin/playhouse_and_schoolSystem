<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Pivot table for parent_info and guardians (many-to-many)
     */
    public function up(): void
    {
        Schema::create('parent_guardian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_info_id')->constrained('parent_info')->onDelete('cascade');
            $table->foreignId('guardian_id')->constrained('guardians')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_guardian');
    }
};
