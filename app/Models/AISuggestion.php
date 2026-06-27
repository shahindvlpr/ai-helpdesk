<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AISuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'suggestion',
        'type',
        'confidence_score',
        'is_used',
        'used_at',
        'metadata'
    ];

    protected $casts = [
        'suggestion' => 'array',
        'confidence_score' => 'float',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeHighConfidence($query, $threshold = 0.7)
    {
        return $query->where('confidence_score', '>=', $threshold);
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    public function markAsUsed()
    {
        $this->is_used = true;
        $this->used_at = now();
        $this->save();
        return $this;
    }

    public function getFormattedSuggestion()
    {
        if (is_array($this->suggestion)) {
            return json_encode($this->suggestion, JSON_PRETTY_PRINT);
        }
        return $this->suggestion;
    }
}