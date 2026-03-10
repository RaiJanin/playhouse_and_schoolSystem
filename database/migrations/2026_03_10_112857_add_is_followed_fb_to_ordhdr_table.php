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
            if (!Schema::hasColumn('is_followed_fb', 'ordhdr')) {
                $table->boolean('is_followed_fb')->default(false)->after('fb_pp_url');
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
