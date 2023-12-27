<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'unit_id',
        'is_published',
        'user_id',
    ];
    protected static function booted() {
        static::creating(function($model) {
            $model->user_id = Auth::user()->id;
        });
    }
    public function Unit()
    {
        return $this->belongsTo(related:Unit::class);
    }
    public function SubTask()
    {
        return $this->hasMany(related:SubTask::class);
    }
    public function DetailTask()
    {
        return $this->hasMany(related:DetailTask::class);
    }

    public function DetailCostTask()
    {
        return $this->hasMany(related:DetailCostTask::class);
    }

    public function DetailCostSubTask()
    {
        return $this->hasMany(related:DetailCostSubTask::class);
    }
    public function User()
    {
        return $this->belongsTo(related:User::class);
    }
}
