<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DetailSubTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'koefisien',
        'component_id',
        'sub_task_id',
        'user_id',
    ];

    protected static function booted() {
        static::creating(function($model) {
            $model->user_id = Auth::user()->id;
        });
    }
    public function Component()
    {
        return $this->belongsTo(related:Component::class);
    }
    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
}
