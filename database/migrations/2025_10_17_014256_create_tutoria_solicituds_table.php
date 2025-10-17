<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tutoria_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutoria_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('users')->cascadeOnDelete();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->timestamps();
            $table->unique(['tutoria_id', 'alumno_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('tutoria_solicitudes');
    }
};
