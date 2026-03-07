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
        if(!Schema::hasTable('m06_guardian'))
        {
            Schema::create('m06_guardian', function (Blueprint $table) {
                $table->string('d_code_g', 50)->primary();
                $table->string('d_code');
                $table->foreign('d_code')->references('d_code')->on('m06')->onDelete('cascade');
                $table->string('d_code_c');
                $table->foreign('d_code_c')->references('d_code_c')->on('m06_child')->onDelete('cascade');
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
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m06_guardian');
    }
};
