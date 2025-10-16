<?php
namespace App\Livewire\Admin;
use Livewire\Component;
    use App\Models\User;
    use App\Models\Course;

    class AdminDashboard extends Component
  
    {
        public $showProfesorModal = false;
        public $showCursoModal = false;
        public $profesor = [
            'name' => '',
            'email' => '',
            'password' => '',
            'course_id' => '',
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
                'course_id' => $this->profesor['course_id'],
            ]);
            $this->profesor = ['name' => '', 'email' => '', 'password' => '', 'course_id' => ''];
            $this->hideAddProfesorModal();
        }

        public function agregarCurso()
        {
            $this->validate([
                'curso.name' => 'required|string|max:255|unique:courses,name',
                'curso.description' => 'required|string',
            ], [
                'curso.name.unique' => 'El nombre del curso ya existe.',
            ]);
            Course::create([
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
                $profesor->course_id = null;
                $profesor->save();
                $profesor->delete();
                session()->flash('mensaje', 'Profesor eliminado correctamente y el curso ha quedado libre.');
            } else {
                session()->flash('error', 'No se encontró el profesor.');
            }
        }

        public function eliminarCurso($id)
        {
            $profesorOcupando = User::where('role_id', 2)->where('course_id', $id)->first();
            if ($profesorOcupando) {
                session()->flash('error', 'Este curso está ocupado por un profesor, no se puede eliminar.');
                return;
            }
            $curso = Course::find($id);
            if ($curso) {
                $curso->delete();
                session()->flash('mensaje', 'Curso eliminado correctamente.');
            }
        }

        public function render()
        {
            $profesores = User::where('role_id', 2)->with('course')->get();
            $cursos = Course::all();
            return view('livewire.admin.dashboard', compact('profesores', 'cursos'));
        }
    }
