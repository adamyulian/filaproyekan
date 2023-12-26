<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_published',
        'user_id',
        'softdeletes'

    ];

    protected static function booted() {
        static::creating(function($model) {
            $model->user_id = Auth::user()->id;
        });
    }

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }

    public function User()
    {
        return $this->belongsTo(related:User::class);
    }

    public function Subtask()
    {
        return $this->hasMany(related:SubTask::class);
    }

}
