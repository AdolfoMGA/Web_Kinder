<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('asignaparentescos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('parentesco_id');
            $table->string('nombre');
            $table->string('apellido_p');
            $table->string('apellido_m');
            $table->string('telefono')->nullable();
            $table->string('domicilio')->nullable();
            $table->string('correo')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreign('estudiante_id')
                  ->references('id')
                  ->on('estudiantes')
                  ->onDelete('cascade');
                  
            $table->foreign('parentesco_id')
                  ->references('id')
                  ->on('parentescos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    { 
        Schema::dropIfExists('asignaparentescos');
    }
};