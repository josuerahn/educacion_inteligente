<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->date('fecha_limite'); // corregido nombre del campo
            $table->string('archivo')->nullable();

            // Relación con profesor (usuario con rol de profesor)
            $table->foreignId('profesor_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Relación con tutoría
            $table->foreignId('tutoria_id')
                  ->constrained('tutorias')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
