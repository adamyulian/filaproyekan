<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'website_url',
        'industri',
        'softdeletes',
        'is_published'
    ];

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }
}
