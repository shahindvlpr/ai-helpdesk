// app/Models/TicketLog.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
        'description',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    // Relationships
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper Methods
    public static function log($ticketId, $userId, $action, $description, $oldValue = null, $newValue = null)
    {
        return self::create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => $action,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}