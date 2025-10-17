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
    public function profesor()
    {
        return $this->belongsTo(\App\Models\User::class, 'profesor_id');
    }
    public function tareas()
    {
        return $this->hasMany(\App\Models\Tarea::class, 'tutoria_id');
    }
    public function entregas()
    {
        return $this->hasManyThrough(
            \App\Models\Entrega::class,
            \App\Models\Tarea::class,
            'tutoria_id', // Foreign key on Tarea table...
            'tarea_id',   // Foreign key on Entrega table...
            'id',         // Local key on Tutoria table...
            'id'          // Local key on Tarea table...
        );
    }
}
