<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }

    public function User()
    {
        return $this->belongsTo(related:User::class);
    }

}
