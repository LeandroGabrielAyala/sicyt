<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentacion extends Model
{
    protected $fillable = [
        'postulacion_id',
        'nombre',
        'archivo',
        'tipo',
        'fecha',
    ];

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class);
    }
}

