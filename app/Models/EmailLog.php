<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'recipient',
        'subject',
        'body',
        'status',
        'sent_at',
        'error_message',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByRecipient($query, $email)
    {
        return $query->where('recipient', $email);
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
        return $this;
    }

    public function markAsFailed($errorMessage)
    {
        $this->status = 'failed';
        $this->error_message = $errorMessage;
        $this->save();
        return $this;
    }

    public static function logEmail($recipient, $subject, $body, $ticketId = null, $userId = null, $metadata = null)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'recipient' => $recipient,
            'subject' => $subject,
            'body' => $body,
            'status' => 'pending',
            'metadata' => $metadata,
        ]);
    }
}