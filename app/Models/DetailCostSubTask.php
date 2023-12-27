<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DetailCostSubTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'volume',
        'cost_component_id',
        'sub_task_id',
        'user_id',
    ];

    protected static function booted() {
        static::creating(function($model) {
            $model->user_id = Auth::user()->id;
        });
    }
    public function CostComponent()
    {
        return $this->belongsTo(related:CostComponent::class);
    }
    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
    public function Task()
    {
        return $this->belongsTo(related:Task::class);
    }
}
