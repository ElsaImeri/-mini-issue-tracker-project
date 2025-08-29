<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_id',
        'author_name',
        'body',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the issue that owns the comment.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Scope a query to only include comments for a specific issue.
     */
    public function scopeForIssue($query, $issueId)
    {
        return $query->where('issue_id', $issueId);
    }

    /**
     * Scope a query to order comments by latest first.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the excerpt of the comment body.
     */
    public function getExcerptAttribute(): string
    {
        return strlen($this->body) > 100 
            ? substr($this->body, 0, 100) . '...' 
            : $this->body;
    }
}