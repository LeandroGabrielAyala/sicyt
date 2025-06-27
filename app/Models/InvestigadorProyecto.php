<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Casts\Attribute;

class InvestigadorProyecto extends Pivot
{
    protected $table = 'investigador_proyecto';

    protected $fillable = [
        'proyecto_id',
        'investigador_id',
        'funcion_id',
        'inicio',
        'fin',
        'disposicion', 
        'resolucion',
        'pdf_disposicion',
        'pdf_resolucion',
        'vigente',
    ];

    protected $casts = [
        'inicio' => 'date',
        'fin' => 'date',
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
        'vigente' => 'boolean',
    ];

    public function funcion()
    {
        return $this->belongsTo(\App\Models\Funcion::class);
    }

}
