// app/Models/Agent.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'skills',
        'max_tickets',
        'is_available',
        'rating',
        'total_tickets_handled',
        'avg_response_time',
        'avg_resolution_time',
        'last_active_at'
    ];

    protected $casts = [
        'skills' => 'array',
        'is_available' => 'boolean',
        'rating' => 'float',
        'max_tickets' => 'integer',
        'total_tickets_handled' => 'integer',
        'avg_response_time' => 'float',
        'avg_resolution_time' => 'float',
        'last_active_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'agent_id');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->is_available && $this->getCurrentTicketCount() < $this->max_tickets;
    }

    public function getCurrentTicketCount()
    {
        return $this->tickets()->whereIn('status', ['open', 'in_progress'])->count();
    }

    public function getAvailableSlots()
    {
        return max(0, $this->max_tickets - $this->getCurrentTicketCount());
    }

    public function updateRating()
    {
        $avgRating = $this->tickets()
            ->whereNotNull('rating')
            ->average('rating');
        
        $this->rating = $avgRating ?? 0;
        $this->save();

        return $this;
    }
}