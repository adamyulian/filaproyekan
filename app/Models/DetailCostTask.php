<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailCostTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'volume',
        'sub_task_id',
        'task_id'
    ];
    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
    public function DetailSubTask()
    {
        return $this->hasMany(related:DetailSubTask::class);
    }
    public function Task()
    {
        return $this->belongsTo(related:Task::class);
    }
}
