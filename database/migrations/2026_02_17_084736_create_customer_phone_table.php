<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main phone numbers table
        Schema::create('phone_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number')->unique();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // Pivot table: customer_phone (many-to-many)
        Schema::create('customer_phone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_record_id')->constrained('customer_records')->onDelete('cascade');
            $table->foreignId('phone_number_id')->constrained('phone_numbers')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            // Prevent duplicate relationships
            $table->unique(['customer_record_id', 'phone_number_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_phone');
        Schema::dropIfExists('phone_numbers');
    }
};