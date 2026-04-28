<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsBlastRecipient extends Model
{
    protected $fillable = [
        'sms_blast_id',
        'recipient_type',
        'recipient_id',
        'recipient_name',
        'mobile_number',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    public function smsBlast(): BelongsTo
    {
        return $this->belongsTo(SmsBlast::class);
    }
}
