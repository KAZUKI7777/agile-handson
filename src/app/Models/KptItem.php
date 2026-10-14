<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_id',
        'type',
        'content',
        'author',
        'votes',
        'is_actioned',
    ];

    protected $casts = [
        'votes' => 'integer',
        'is_actioned' => 'boolean',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }
}
