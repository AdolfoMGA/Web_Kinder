<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    use HasFactory;

    // Indica las columnas que pueden ser llenadas de manera masiva
    protected $fillable = [
        'descripcion',
    ];
}