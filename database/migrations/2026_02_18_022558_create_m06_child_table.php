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
        if(!Schema::hasTable('m06_child')) {
            Schema::create('m06_child', function (Blueprint $table) {
                $table->string('d_code_c', 50)->primary();
                $table->string('lastname', 100);
                $table->string('firstname', 100);
                $table->date('birthday');
                $table->integer('age')->nullable();
                $table->string('photo')->nullable();
                $table->string('phoneno', 20)->nullable();
                $table->string('createdby', 50)->nullable();
                $table->string('updatedby', 50)->nullable();
                $table->string('d_code', 50);
                
                // Foreign key constraint
                $table->foreign('d_code')->references('d_code')->on('m06')->onDelete('cascade');
                
                $table->timestamps(); // Adds created_at and updated_at
            });

            return;
        }

        // TABLE EXISTS → ALTER IT
        Schema::table('m06_child', function (Blueprint $table) {

            if (!Schema::hasColumn('m06_child', 'lastname'))
                $table->string('lastname', 100);

            if (!Schema::hasColumn('m06_child', 'firstname'))
                $table->string('firstname', 100);

            if (!Schema::hasColumn('m06_child', 'birthday'))
                $table->date('birthday');

            if (!Schema::hasColumn('m06_child', 'age'))
                $table->integer('age')->nullable();

            if (!Schema::hasColumn('m06_child', 'photo'))
                $table->string('photo')->nullable();

            if (!Schema::hasColumn('m06_child', 'phoneno'))
                $table->string('phoneno', 20)->nullable();

            if (!Schema::hasColumn('m06_child', 'createdby'))
                $table->string('createdby', 50)->nullable();

            if (!Schema::hasColumn('m06_child', 'updatedby'))
                $table->string('updatedby', 50)->nullable();

            if (!Schema::hasColumn('m06_child', 'd_code'))
                $table->string('d_code', 50);

            if (!Schema::hasColumn('m06_child', 'created_at') &&
                !Schema::hasColumn('m06_child', 'updated_at'))
                $table->timestamps();
        });

        Schema::table('m06_child', function ($table) {
            $fkExists = DB::select("
                SELECT constraint_name
                FROM information_schema.table_constraints
                WHERE table_name = 'm06_child'
                AND constraint_type = 'FOREIGN KEY'
                AND constraint_name = 'm06_child_d_code_foreign';
            ");

            if (!$fkExists) {
                $table->foreign('d_code')
                    ->references('d_code')
                    ->on('m06')
                    ->cascadeOnDelete();
            }
        });

        Log::info('Table m06_child already exists — missing columns added if any.');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Log::info('Rollback skipped: m06_child table is not dropped.');
       // Schema::dropIfExists('m06_child');
    }
};