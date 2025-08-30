<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'deadline',
        'user_id', // Shto këtë për të identifikuar pronarin e projektit
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }

    // Shto këtë lidhje për autorizim
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}