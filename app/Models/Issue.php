<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public static function getStatuses(): array
    {
        return [
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'closed' => 'Closed'
        ];
    }

    public static function getPriorities(): array
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High'
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'issue_user');
    }

    public function getStatusColorAttribute(): string
    {
        return [
            'open' => 'blue',
            'in_progress' => 'yellow',
            'closed' => 'green'
        ][$this->status] ?? 'gray';
    }

    public function getPriorityColorAttribute(): string
    {
        return [
            'low' => 'gray',
            'medium' => 'orange',
            'high' => 'red'
        ][$this->priority] ?? 'gray';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast();
    }
}