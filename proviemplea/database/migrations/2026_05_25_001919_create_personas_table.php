<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('email')->unique();
            $table->string('telefono');
            $table->string('codigo_talento');
            $table->string('nivel_educacional');
            $table->string('titulo_carrera');
            $table->integer('anios_experiencia');

            $table->json('competencias');

            $table->string('tipo_jornada');
            $table->string('modalidad');

            $table->boolean('validado')->default(false);
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
