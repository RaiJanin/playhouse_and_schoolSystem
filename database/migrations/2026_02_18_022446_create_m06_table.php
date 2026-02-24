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
        Schema::create('m06', function (Blueprint $table) {
            $table->string('d_code', 50)->primary();
            $table->string('d_name', 100)->nullable();
            $table->string('lastname', 100);
            $table->string('firstname', 100);
            $table->string('mi', 10)->nullable(); // Middle Initial
            $table->date('birthday')->nullable();
            $table->string('mobileno', 20);
            $table->string('email')->nullable();
            $table->boolean('isparent')->default(true);
            $table->boolean('isguardian')->default(false);
            $table->boolean('guardianauthorized')->default(false);
            $table->string('createdby', 50)->nullable();
            $table->string('updatedby', 50)->nullable();
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m06');
    }
};