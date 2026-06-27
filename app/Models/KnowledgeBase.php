// app/Models/KnowledgeBase.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KnowledgeBase extends Model
{
    use HasFactory;

    protected $table = 'knowledge_bases';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'tags',
        'views',
        'helpful_count',
        'not_helpful_count',
        'created_by',
        'is_published',
        'ai_metadata'
    ];

    protected $casts = [
        'tags' => 'array',
        'ai_metadata' => 'array',
        'is_published' => 'boolean',
        'views' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(KnowledgeCategory::class, 'category_id');
    }

    // Boot Method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });

        static::updating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%{$term}%")
                     ->orWhere('content', 'LIKE', "%{$term}%")
                     ->orWhere('tags', 'LIKE', "%{$term}%");
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    // Helper Methods
    public function incrementViews()
    {
        $this->increment('views');
        return $this;
    }

    public function markHelpful()
    {
        $this->increment('helpful_count');
        return $this;
    }

    public function markNotHelpful()
    {
        $this->increment('not_helpful_count');
        return $this;
    }
}