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
        if(!Schema::hasTable('m06')) {
            Schema::create('m06', function (Blueprint $table) {
                $table->string('d_code', 50)->primary();
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
                $table->timestamps(); // Adds created_at and updated_at
            });
            return;
        }

        Schema::table('m06', function (Blueprint $table) {

            if (!Schema::hasColumn('m06', 'd_name'))
                $table->string('d_name', 100)->nullable();

            if (!Schema::hasColumn('m06', 'lastname'))
                $table->string('lastname', 100);

            if (!Schema::hasColumn('m06', 'firstname'))
                $table->string('firstname', 100);

            if (!Schema::hasColumn('m06', 'mi'))
                $table->string('mi', 10)->nullable();

            if (!Schema::hasColumn('m06', 'birthday'))
                $table->date('birthday')->nullable();

            if (!Schema::hasColumn('m06', 'mobileno'))
                $table->string('mobileno', 20);

            if (!Schema::hasColumn('m06', 'email'))
                $table->string('email')->nullable();

            if (!Schema::hasColumn('m06', 'isparent'))
                $table->boolean('isparent')->default(true);

            if (!Schema::hasColumn('m06', 'isguardian'))
                $table->boolean('isguardian')->default(false);

            if (!Schema::hasColumn('m06', 'guardianauthorized'))
                $table->boolean('guardianauthorized')->default(false);

            if (!Schema::hasColumn('m06', 'createdby'))
                $table->string('createdby', 50)->nullable();

            if (!Schema::hasColumn('m06', 'updatedby'))
                $table->string('updatedby', 50)->nullable();

            if (!Schema::hasColumn('m06', 'created_at') &&
                !Schema::hasColumn('m06', 'updated_at'))
                $table->timestamps();
        });

        Log::info('Table m06 already exists — missing columns added if any.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::info('Rollback skipped: m06 table is not dropped.');
        //Schema::dropIfExists('m06');
    }
};