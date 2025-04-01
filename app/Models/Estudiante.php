<?php

// app/Models/Estudiante.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    // Indica las columnas que pueden ser llenadas de manera masiva
    protected $fillable = [
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'edad',
        'grado_id',
        'grupo_id',
        'docente_id',
    ];

    /**
     * Relación con el modelo Grado.
     */
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    /**
     * Relación con el modelo Grupo.
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    /**
     * Relación con el modelo Docente.
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }
}
