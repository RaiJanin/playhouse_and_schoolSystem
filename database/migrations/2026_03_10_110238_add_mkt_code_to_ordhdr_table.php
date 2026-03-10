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
        Schema::table('ordhdr', function (Blueprint $table) {
            if (!Schema::hasColumn('mkt_code', 'ordhdr')) {
                $table->string('mkt_code', 10)->nullable()->after('parent');
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
