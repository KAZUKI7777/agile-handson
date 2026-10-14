<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    /**
     * 複数代入可能な属性
     */
    protected $fillable = [
        'title',
        'completed',
    ];

    /**
     * 属性のキャスト
     */
    protected $casts = [
        'completed' => 'boolean',
    ];
}