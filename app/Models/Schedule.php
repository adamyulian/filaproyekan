<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sub_task_id',
        'start',
        'finish',
        'user_id',
        'is_published'
    ];

    public function Task()
    {
        return $this->belongsTo(related:Task::class);
    }
    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
}
