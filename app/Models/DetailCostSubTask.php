<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailCostSubTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'volume',
        'cost_component_id',
        'sub_task_id'
    ];
    public function CostComponent()
    {
        return $this->belongsTo(related:CostComponent::class);
    }
    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
}
