<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostComponent extends Model
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
    public function Unit()
    {
        return $this->belongsTo(related:Unit::class);
    }
    public function Brand()
    {
        return $this->belongsTo(related:Brand::class);
    }

    public function User()
    {
        return $this->belongsTo(related:User::class);
    }
}
