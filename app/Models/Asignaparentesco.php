<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Asignaparentesco extends Model
{
    protected $fillable = [
        'estudiante_id',
        'parentesco_id',
        'nombre',
        'apellido_p',
        'apellido_m',
        'telefono',
        'domicilio',
        'correo',
        'foto' // Asegúrate de tener este campo
    ];
    
    // Accessor para la URL completa de la foto
    public function getFotoUrlAttribute()
    {
        return $this->foto ? Storage::url($this->foto) : null;
    }
    
    // Relaciones si las necesitas
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
    
    public function parentesco()
    {
        return $this->belongsTo(Parentesco::class);
    }
}