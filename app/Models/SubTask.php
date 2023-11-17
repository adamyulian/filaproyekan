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
        'is_published'
    ];
    public function Unit()
    {
        return $this->belongsTo(related:Unit::class);
    }
    public function DetailHspk()
    {
        return $this->hasMany(related:DetailHspk::class);
    }
    public function User()
    {
        return $this->belongsTo(related:User::class);
    }
}
