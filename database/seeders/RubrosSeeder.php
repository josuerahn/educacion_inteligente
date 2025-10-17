<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tutoria;
use App\Models\Rubro;

class RubrosSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'Programación' => ['Sintaxis','Estructuras','Funciones','POO','Debug'],
            'Matemática'   => ['Aritmética','Álgebra','Geometría','Funciones','Problemas'],
            'Metodología'  => ['Comprensión','Redacción','Citas','Estructura','Presentación'],
        ];

        foreach ($map as $tutoriaNombre => $rubros) {
            $tutoria = Tutoria::where('name',$tutoriaNombre)->first();
            if (!$tutoria) continue;

            foreach ($rubros as $r) {
                Rubro::firstOrCreate(
                    ['tutoria_id'=>$tutoria->id, 'nombre'=>$r],
                    ['peso'=>0.20]
                );
            }
        }
    }
}
