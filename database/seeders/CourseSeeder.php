<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Tutoria;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Tutoria::create(['name' => 'Curso 1', 'description' => 'Descripción del curso 1']);
        Tutoria::create(['name' => 'Curso 2', 'description' => 'Descripción del curso 2']);
    }
}
