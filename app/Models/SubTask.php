<?php

namespace App\Models;

use App\Models\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubTask extends Model
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
    public function DetailSubTask()
    {
        return $this->hasMany(related:DetailSubTask::class);
    }
    public function User()
    {
        return $this->belongsTo(related:User::class);
    }

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }
}
