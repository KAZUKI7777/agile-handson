<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'goal',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(ScrumTask::class);
    }

    public function kptItems(): HasMany
    {
        return $this->hasMany(KptItem::class);
    }

    /**
     * 総ストーリーポイント
     */
    public function totalPoints(): int
    {
        return (int) $this->tasks()->sum('story_points');
    }

    /**
     * 完了済みストーリーポイント
     */
    public function completedPoints(): int
    {
        return (int) $this->tasks()->where('status', 'done')->sum('story_points');
    }

    /**
     * 進捗率 (%)
     */
    public function progressPercent(): int
    {
        $total = $this->totalPoints();
        if ($total === 0) {
            $taskCount = $this->tasks()->count();
            if ($taskCount === 0) return 0;
            return (int) round(($this->tasks()->where('status', 'done')->count() / $taskCount) * 100);
        }
        return (int) round(($this->completedPoints() / $total) * 100);
    }
}
