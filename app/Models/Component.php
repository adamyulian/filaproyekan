<?php

namespace App\Models;

use App\Models\SubTask;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Component extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'jenis',
        'unit_id',
        'user_id',
        'hargaunit',
        'deskripsi',
        'brand_id'
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
    public function Brand()
    {
        return $this->belongsTo(related:Brand::class);
    }
    public function DetailSubTask()
    {
        return $this->hasMany(related:DetailSubTask::class);
    }

    public function User()
    {
        return $this->belongsTo(related:User::class);
    }

    public function SubTask()
    {
        return $this->belongsTo(related:SubTask::class);
    }
}
