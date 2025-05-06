<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignadocente extends Model
{
    use HasFactory;

    protected $table = 'asignadocentes'; // Nombre de la tabla

    // Definir los campos que se pueden asignar masivamente
    protected $fillable = [
        'docente_id',
        'grado_id',
        'grupo_id',
    ];

    // Relación con el modelo Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class);
    }

    // Relación con el modelo Grado
    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    // Relación con el modelo Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
