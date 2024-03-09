<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Post;
use App\Models\Attendance;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Stevebauman\Location\Facades\Location;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable 
implements MustVerifyEmail, FilamentUser
{

    
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    // }

    public function Component()
    {
        return $this->hasMany(related:Component::class);
    }

    public function Unit()
    {
        return $this->hasMany(related:Unit::class);
    }

    public function Post()
    {
        return $this->hasMany(related:Post::class);
    }
    
    public function Attendance()
    {
        return $this->hasMany(related:Attendance::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasVerifiedEmail();
    }
    
}
