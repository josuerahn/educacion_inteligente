<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run()
    {
        Course::create(['name' => 'Curso 1', 'description' => 'Descripción del curso 1']);
        Course::create(['name' => 'Curso 2', 'description' => 'Descripción del curso 2']);
    }
}
