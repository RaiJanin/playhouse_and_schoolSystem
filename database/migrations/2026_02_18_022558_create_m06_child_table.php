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
        Schema::create('m06_child', function (Blueprint $table) {
            $table->string('d_code_c', 50)->primary();
            $table->string('lastname', 100);
            $table->string('firstname', 100);
            $table->date('birthday');
            $table->integer('age')->nullable();
            $table->string('createdby', 50)->nullable();
            $table->date('createddate')->nullable();
            $table->time('createdtime')->nullable();
            $table->string('updatedby', 50)->nullable();
            $table->date('updateddate')->nullable();
            $table->time('updatedtime')->nullable();
            $table->string('d_code', 50);
            
            // Foreign key constraint
            $table->foreign('d_code')->references('d_code')->on('m06')->onDelete('cascade');
            
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m06_child');
    }
};