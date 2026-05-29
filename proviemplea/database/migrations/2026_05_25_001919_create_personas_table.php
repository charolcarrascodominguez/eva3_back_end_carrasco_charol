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
            $table->string('codigo_talento')->nullable();
            $table->string('nivel_educacional')->nullable();
            $table->string('titulo_carrera')->nullable();
            $table->integer('anios_experiencia')->default(0);
            $table->json('competencias')->nullable();
            $table->string('tipo_jornada')->nullable();
            $table->string('modalidad')->nullable();
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
