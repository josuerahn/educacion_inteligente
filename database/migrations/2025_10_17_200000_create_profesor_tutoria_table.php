<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profesor_tutoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutoria_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profesor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('capacity')->default(10); // cupos por profesor para esa tutoria
            $table->timestamps();
            $table->unique(['tutoria_id','profesor_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('profesor_tutoria');
    }
};
