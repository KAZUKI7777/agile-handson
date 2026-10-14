<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScrumTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_id',
        'title',
        'description',
        'as_a',
        'i_want',
        'so_that',
        'acceptance_criteria',
        'assignee',
        'story_points',
        'priority',
        'status',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }
}
