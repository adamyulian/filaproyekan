<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

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

    // protected static function booted() {
    //     static::creating(function($model) {
    //         $model->user_id = Auth::user()->id;
    //     });
    // }

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }
}
