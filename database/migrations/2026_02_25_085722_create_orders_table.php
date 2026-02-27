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
        if(!Schema::hasTable('ordhdr')) {
            Schema::create('ordhdr', function (Blueprint $table) {
                $table->id();
                $table->string('ord_code_ph')->unique();
                $table->string('d_code');
                $table->foreign('d_code')
                    ->references('d_code')
                    ->on('m06')
                    ->cascadeOnDelete();
                $table->string('guardian', 100)->nullable();
                $table->decimal('xtra_chrg_amnt', 10, 2)->default(0.00);
                $table->decimal('total_amnt', 10, 2)->default(0.00);
                $table->decimal('disc_amnt', 10, 2)->nullable();
                $table->timestamps();
            });
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::info('Rollback skipped: ordhdr table is not dropped.');
        // Schema::dropIfExists('ordhdr');
    }
};
