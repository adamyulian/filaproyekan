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
        'softdeletes'
    ];

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }

    public function Hspk()
    {
        return $this->hasMany(related:Hspk::class);
    }
    public function Project()
    {
        return $this->hasMany(related:Project::class);
    }
}
