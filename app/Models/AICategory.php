// app/Models/AICategory.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AICategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'confidence_threshold',
        'is_active',
        'color',
        'metadata'
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
        'confidence_threshold' => 'float',
        'metadata' => 'array',
    ];

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByKeywords($query, $text)
    {
        return $query->where(function ($q) use ($text) {
            $q->where('name', 'LIKE', "%{$text}%")
              ->orWhere('description', 'LIKE', "%{$text}%")
              ->orWhere('keywords', 'LIKE', "%{$text}%");
        });
    }

    // Helper Methods
    public function matchesKeywords($text)
    {
        if (empty($this->keywords)) {
            return false;
        }

        $text = strtolower($text);
        foreach ($this->keywords as $keyword) {
            if (strpos($text, strtolower($keyword)) !== false) {
                return true;
            }
        }
        return false;
    }

    public function calculateConfidence($text)
    {
        $matches = 0;
        $total = count($this->keywords ?? []);

        if ($total === 0) {
            return 0;
        }

        $text = strtolower($text);
        foreach ($this->keywords as $keyword) {
            if (strpos($text, strtolower($keyword)) !== false) {
                $matches++;
            }
        }

        return $matches / $total;
    }
}