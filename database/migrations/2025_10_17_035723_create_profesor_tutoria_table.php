<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('profesor_tutoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tutoria_id')->constrained('tutorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('profesor_tutoria');
    }
};

