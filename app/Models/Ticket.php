// app/Models/Ticket.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'agent_id',
        'category_id',
        'subject',
        'description',
        'priority',
        'status',
        'ai_summary',
        'ai_suggestions',
        'metadata',
        'resolved_at',
        'closed_at',
        'response_time',
        'resolution_time'
    ];

    protected $casts = [
        'ai_suggestions' => 'array',
        'metadata' => 'array',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function aiSuggestions()
    {
        return $this->hasMany(AISuggestion::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getPriorityColorAttribute()
    {
        return [
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red'
        ][$this->priority] ?? 'gray';
    }

    public function getStatusColorAttribute()
    {
        return [
            'open' => 'blue',
            'in_progress' => 'purple',
            'resolved' => 'green',
            'closed' => 'gray'
        ][$this->status] ?? 'gray';
    }

    // Helper Methods
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function assignTo(User $agent)
    {
        $this->agent_id = $agent->id;
        $this->status = 'in_progress';
        $this->save();

        return $this;
    }

    public function resolve()
    {
        $this->status = 'resolved';
        $this->resolved_at = now();
        $this->resolution_time = $this->created_at->diffInMinutes(now());
        $this->save();

        return $this;
    }
}