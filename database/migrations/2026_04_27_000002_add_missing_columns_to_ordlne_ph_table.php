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
            // Add ckin column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'ckin')) {
                $table->timestamp('ckin')->nullable()->after('disc_code');
            }
            
            // Add ckout column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'ckout')) {
                $table->timestamp('ckout')->nullable()->after('ckin');
            }
            
            // Add bkin column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'bkin')) {
                $table->timestamp('bkin')->nullable()->after('ckout');
            }
            
            // Add bkout column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'bkout')) {
                $table->timestamp('bkout')->nullable()->after('bkin');
            }
            
            // Add guardian column if not exists (might already exist from another migration)
            if (!Schema::hasColumn('ordlne_ph', 'guardian')) {
                $table->string('guardian', 100)->nullable()->after('d_code_child');
            }
            
            // Add isfreeze column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'isfreeze')) {
                $table->boolean('isfreeze')->default(false)->after('bkout');
            }
            
            // Add notified_timeout column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'notified_timeout')) {
                $table->boolean('notified_timeout')->default(false)->after('isfreeze');
            }
            
            // Add durations_id column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'durations_id')) {
                $table->foreignId('durations_id')->nullable()->after('notified_timeout')
                      ->constrained('duration_prices')->nullOnDelete();
            }
            
            // Add qr_child column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'qr_child')) {
                $table->string('qr_child', 50)->nullable()->after('durations_id');
            }
            
            // Add qr_guardian column if not exists
            if (!Schema::hasColumn('ordlne_ph', 'qr_guardian')) {
                $table->string('qr_guardian', 50)->nullable()->after('qr_child');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordlne_ph', function (Blueprint $table) {
            $table->dropForeign(['durations_id']);
            $table->dropColumn(['ckin', 'ckout', 'bkin', 'bkout', 'guardian', 'isfreeze', 'notified_timeout', 'durations_id', 'qr_child', 'qr_guardian']);
        });
    }
};
