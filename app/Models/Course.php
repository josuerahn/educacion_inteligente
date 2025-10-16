<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name', 'description'];

    public function profesores()
    {
        return $this->hasMany(User::class, 'course_id')->where('role_id', 2); // 2 = profesor
    }
    public function alumnos()
    {
        return $this->hasMany(User::class, 'course_id')->where('role_id', 3); // 3 = alumno
    }
    public function profesor() {
        return $this->belongsTo(User::class, 'profesor_id');
    }
}
