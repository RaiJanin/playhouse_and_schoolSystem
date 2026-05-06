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
        Schema::create('sms_blasts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'failed', 'cancelled'])->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->enum('type', ['automation', 'campaign'])->default('automation');
            $table->string('slug')->nullable();
            $table->enum('send_mode', ['now', 'scheduled', 'alltimes'])->default('alltimes');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'scheduled_at']);
            $table->index('send_mode');
            $table->index('type');
        });
    }

    protected $sqlCreateTables = "
        CREATE TABLE sms_blasts (
            id BIGSERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'draft'
                CHECK (status IN ('draft', 'scheduled', 'sending', 'sent', 'failed', 'cancelled')),
            total_recipients INTEGER NOT NULL DEFAULT 0,
            sent_count INTEGER NOT NULL DEFAULT 0,
            failed_count INTEGER NOT NULL DEFAULT 0,
            type VARCHAR(20) NOT NULL DEFAULT 'automation'
                CHECK (type IN ('automation', 'campaign')),
            send_mode VARCHAR(20) NOT NULL DEFAULT 'alltimes'
                CHECK (send_mode IN ('now', 'scheduled', 'alltimes')),
            scheduled_at TIMESTAMP NULL,
            sent_at TIMESTAMP NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL
        );

        CREATE INDEX sms_blasts_status_scheduled_at_idx
            ON sms_blasts (status, scheduled_at);

        CREATE INDEX sms_blasts_send_mode_idx
            ON sms_blasts (send_mode);

        CREATE INDEX sms_blasts_type_idx
            ON sms_blasts (type);
    ";

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_blasts');
    }
};
