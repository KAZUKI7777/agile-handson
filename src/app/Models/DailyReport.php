<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'department',
        'user_name',
        'work_location',
        'title',
        'content',
        'issues',
        'next_actions',
        'insights',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];
}
