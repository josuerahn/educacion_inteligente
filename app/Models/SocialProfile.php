<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'linkedin',
        'github',
        'gitlab',
        'wordpress',
        'notion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}