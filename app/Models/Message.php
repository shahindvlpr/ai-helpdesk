// app/Models/Message.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'type',
        'is_internal',
        'metadata',
        'read_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
        'is_internal' => 'boolean',
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
    public function scopeCustomerMessages($query)
    {
        return $query->where('type', 'customer');
    }

    public function scopeAgentMessages($query)
    {
        return $query->where('type', 'agent');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->read_at = now();
        $this->save();
        return $this;
    }

    public function isFromCustomer()
    {
        return $this->type === 'customer';
    }

    public function isFromAgent()
    {
        return $this->type === 'agent';
    }
}