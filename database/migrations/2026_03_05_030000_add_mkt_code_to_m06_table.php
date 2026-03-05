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
        Schema::table('m06', function (Blueprint $table) {
            if (!Schema::hasColumn('m06', 'mkt_code')) {
                $table->string('mkt_code', 50)->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m06', function (Blueprint $table) {
            $table->dropColumn('mkt_code');
        });
    }
};
