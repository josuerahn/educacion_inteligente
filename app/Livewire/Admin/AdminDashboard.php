<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Tutoria;


class AdminDashboard extends Component

{
    public $showProfesorModal = false;
    public $showCursoModal = false;
    public $profesor = [
        'name' => '',
        'email' => '',
        'password' => '',
        'tutoria_id' => '',
    ];
    public $curso = [
        'name' => '',
        'description' => '',
    ];



    public function showAddProfesorModal()
    {
        $this->showProfesorModal = true;
    }

    public function hideAddProfesorModal()
    {
        $this->showProfesorModal = false;
    }

    public function showAddCursoModal()
    {
        $this->showCursoModal = true;
    }

    public function hideAddCursoModal()
    {
        $this->showCursoModal = false;
    }

    public function agregarProfesor()
    {
        $this->validate([
            'profesor.name' => 'required|string|max:255',
            'profesor.email' => 'required|email|unique:users,email',
            'profesor.password' => 'required|string|min:6',
            'profesor.course_id' => 'required|exists:courses,id',
        ]);
        User::create([
            'name' => $this->profesor['name'],
            'email' => $this->profesor['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($this->profesor['password']),
            'role_id' => 2,
            'tutoria_id' => $this->profesor['tutoria_id'],
        ]);
        $this->profesor = ['name' => '', 'email' => '', 'password' => '', 'tutoria_id' => ''];
        $this->hideAddProfesorModal();
    }

    public function agregarTutoria()
    {
        $this->validate([
            'tutoria.name' => 'required|string|max:255|unique:tutorias,name',
            'tutoria.description' => 'required|string',
        ], [
            'tutoria.name.unique' => 'El nombre de la tutoria ya existe.',
        ]);
        Tutoria::create([
            'name' => $this->curso['name'],
            'description' => $this->curso['description'],
        ]);
        $this->curso = ['name' => '', 'description' => ''];
        $this->hideAddCursoModal();
    }

    public function eliminarProfesor($id)
    {
        $profesor = User::where('id', $id)->where('role_id', 2)->first();
        if ($profesor) {
            // Desocupa el curso
            $profesor->tutoria_id = null;
            $profesor->save();
            $profesor->delete();
            session()->flash('mensaje', 'Profesor eliminado correctamente y el curso ha quedado libre.');
        } else {
            session()->flash('error', 'No se encontró el profesor.');
        }
    }

    public function eliminarCurso($id)
    {
        $profesorOcupando = User::where('role_id', 2)->where('tutoria_id', $id)->first();
        if ($profesorOcupando) {
            session()->flash('error', 'Este curso está ocupado por un profesor, no se puede eliminar.');
            return;
        }
        $tutoria = Tutoria::find($id);
        if ($tutoria) {
            $tutoria->delete();
            session()->flash('mensaje', 'Tutoria eliminada correctamente.');
        }
    }

    public function render()
    {
        $profesores = User::where('role_id', 2)->with('tutoria')->get();
        $tutoria = Tutoria::all();
        return view('livewire.admin.dashboard', compact('profesores', 'tutoria'));
    }
}
