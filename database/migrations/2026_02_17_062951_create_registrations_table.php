<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_record_id')->constrained('customer_records')->onDelete('cascade');
            $table->string('registration_type')->default('new'); // new or returnee
            $table->string('status')->default('completed');
            $table->string('qr_code')->nullable();
            $table->string('order_number')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};