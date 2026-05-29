<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('nombre_empresa');
            $table->string('rut_empresa')->unique();
            $table->string('email')->nullable();
            $table->string('tipo_empresa')->nullable();
            $table->string('rubro')->nullable();
            $table->json('beneficios')->nullable();
            $table->boolean('validado')->default(false);
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
