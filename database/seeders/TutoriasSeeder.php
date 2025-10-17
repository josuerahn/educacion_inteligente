<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutoria;

class TutoriasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Programación','Matemática','Metodología'] as $nombre) {
            Tutoria::firstOrCreate(['name'=>$nombre]);
        }
    }
}
