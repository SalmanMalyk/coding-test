<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phase_id',
        'user_id',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::updated(function ($task) {
            if (is_null($task->completed_at) && $task->phase?->is_completion_phase == 1) {
                $task->completed_at = now();
                $task->save();
            }
        });
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
