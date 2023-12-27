<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function Schedule()
    {
        return $this->hasMany(related:Schedule::class);
    }
}
