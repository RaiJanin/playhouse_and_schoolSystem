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
        Schema::table('ordlne_ph', function (Blueprint $table) {
            if (!Schema::hasColumn('guardian', 'ordlne_ph')) {
                $table->string('guardian', 50)->nullable()->after('d_code_child');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordhdr', function (Blueprint $table) {
            //
        });
    }
};
