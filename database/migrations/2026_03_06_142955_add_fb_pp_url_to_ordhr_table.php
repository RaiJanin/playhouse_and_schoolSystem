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
            $table->string('fb_pp_url', 255)->nullable()->after('total_amnt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordhdr', function (Blueprint $table) {
            $table->dropColumn('fb_pp_url');
        });
    }
};
