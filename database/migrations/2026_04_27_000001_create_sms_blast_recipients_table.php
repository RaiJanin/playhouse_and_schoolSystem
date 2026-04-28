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
        Schema::create('sms_blast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_blast_id')->constrained('sms_blasts')->onDelete('cascade');
            $table->string('recipient_type'); // parent, guardian
            $table->string('recipient_id'); // M06 d_code
            $table->string('recipient_name');
            $table->string('mobile_number');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['sms_blast_id', 'status']);
            $table->index('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_blast_recipients');
    }
};
