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
        Schema::create('ordlne_ph', function (Blueprint $table) {
            $table->id();
            $table->string('ord_code_ph');
            $table->foreign('ord_code_ph')
                  ->references('ord_code_ph')
                  ->on('ordhdr')
                  ->cascadeOnDelete();
            $table->string('d_code_child');
            $table->foreign('d_code_child')
                  ->references('d_code_c')
                  ->on('m06_child')
                  ->cascadeOnDelete();
            $table->integer('durationhours');
            $table->decimal('durationsubtotal', 10, 2);
            $table->boolean('issocksadded');
            $table->decimal('socksprice', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->string('disc_code', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('order_items');
    // }
};
