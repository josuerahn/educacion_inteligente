<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Tutoria extends Model
{
    protected $fillable = ['name', 'description'];

    public function profesores()
    {
        return $this->hasMany(User::class, 'tutoria_id')->where('role_id', 2); // 2 = profesor
    }
    public function alumnos()
    {
        return $this->hasMany(User::class, 'tutoria_id')->where('role_id', 3); // 3 = alumno
    }
    public function profesor() {
        return $this->belongsTo(User::class, 'profesor_id');
    }
}
