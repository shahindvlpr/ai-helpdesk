// app/Models/Notification.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
        'read_at',
        'metadata'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
        return $this;
    }

    public function markAsUnread()
    {
        $this->is_read = false;
        $this->read_at = null;
        $this->save();
        return $this;
    }

    public static function createNotification($userId, $title, $message, $type = 'info', $ticketId = null, $link = null)
    {
        return self::create([
            'user_id' => $userId,
            'ticket_id' => $ticketId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false,
        ]);
    }
}