<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumno_tutoria', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alumno_id')->constrained('users');
            $t->foreignId('tutoria_id')->constrained('tutorias');
            $t->enum('nivel_actual', ['inicial','basico','intermedio','avanzado'])->default('inicial');
            $t->unsignedTinyInteger('puntaje_global')->default(0);
            $t->timestamps();
            $t->unique(['alumno_id','tutoria_id']);
        });

        Schema::create('rubros', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tutoria_id')->constrained('tutorias');
            $t->string('nombre');
            $t->decimal('peso', 3, 2)->default(0.20);
            $t->timestamps();
            $t->unique(['tutoria_id','nombre']);
        });

        Schema::create('progreso_rubros', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alumno_tutoria_id')->constrained('alumno_tutoria');
            $t->foreignId('rubro_id')->constrained('rubros');
            $t->unsignedTinyInteger('puntaje')->default(0);
            $t->json('evidencia_json')->nullable();
            $t->timestamp('ultima_actualizacion')->nullable();
            $t->timestamps();
            $t->unique(['alumno_tutoria_id','rubro_id']);
        });

        Schema::create('planes_aprendizaje', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alumno_tutoria_id')->constrained('alumno_tutoria');
            $t->unsignedSmallInteger('version')->default(1);
            $t->text('objetivos')->nullable();
            $t->json('tareas_json');
            $t->json('tips_json')->nullable();
            $t->enum('generado_por', ['ia','profesor'])->default('ia');
            $t->date('vigente_hasta')->nullable();
            $t->timestamps();
        });

        Schema::create('interacciones_ia', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alumno_tutoria_id')->constrained('alumno_tutoria');
            $t->enum('rol', ['alumno','profesor']);
            $t->string('tipo'); // diagnostico, tareas, explicacion, feedback
            $t->longText('entrada');  // prompt
            $t->longText('salida');   // respuesta IA
            $t->integer('tokens')->default(0);
            $t->decimal('costo_usd', 8, 4)->default(0);
            $t->timestamps();
        });

        Schema::create('resumenes_profesor', function (Blueprint $t) {
            $t->id();
            $t->foreignId('profesor_id')->constrained('users');
            $t->foreignId('tutoria_id')->constrained('tutorias');
            $t->date('fecha');
            $t->json('contenido_json');
            $t->timestamps();
            $t->unique(['profesor_id','tutoria_id','fecha']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('resumenes_profesor');
        Schema::dropIfExists('interacciones_ia');
        Schema::dropIfExists('planes_aprendizaje');
        Schema::dropIfExists('progreso_rubros');
        Schema::dropIfExists('rubros');
        Schema::dropIfExists('alumno_tutoria');
    }
};
