<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'deskripsi',
        'unit_id',
        'is_published'
    ];
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
    public function User()
    {
        return $this->belongsTo(related:User::class);
    }
}
