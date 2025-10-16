<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        
        
        
        
        'fecha_nacimiento',
        'role_id',
        'tutoria_id',
        
    ];
    //relacion con rol
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    //relacion con cursos
    public function course()
{
    return $this->belongsTo(Tutoria::class);
}
//relacion con redes sociales

    public function socialProfiles()
{
    return $this->hasMany(\App\Models\SocialProfile::class);
}


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación hasOne para socialProfile (singular)
    public function socialProfile()
    {
        return $this->hasOne(\App\Models\SocialProfile::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}