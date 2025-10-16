<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Tutoria;

class TutoriaSeeder extends Seeder
{
    public function run()
    {
        Tutoria::create(['name' => 'Programacion I', 'description' => 'Enseñanza los fundamentos de la programación.']);
        Tutoria::create(['name' => 'Matematicas', 'description' => 'Las 4 operaciones básicas: suma, resta, multiplicación y división.']);
        Tutoria::create(['name' => 'Programacion II', 'description' => 'Programación avanzada y estructuras de datos.']);
    }
}
