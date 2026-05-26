<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contactos_solicitados', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('empresa_id');
            $table->uuid('persona_id');

            $table->string('estado');
            $table->text('notas_admin')->nullable();

            $table->timestamps();

            // Relaciones
            $table->foreign('empresa_id')
                ->references('id')
                ->on('empresas')
                ->onDelete('cascade');

            $table->foreign('persona_id')
                ->references('id')
                ->on('personas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contactos_solicitados');
    }
};
