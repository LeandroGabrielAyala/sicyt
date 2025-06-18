<?php

namespace App\Models;

use App\Models\Funcion;
use App\Models\Investigador;
use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InvestigadorProyecto extends Pivot
{    
    protected $table = 'investigador_proyecto';

    protected $fillable = [
        'investigador_id',
        'proyecto_id',
        'funcion_id',
        'vigente',
        'inicio',
        'fin',
        'disposicion',
        'resolucion',
        'pdf_disposicion',
        'pdf_resolucion',
    ];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
        'inicio' => 'date',
        'fin' => 'date',
        'vigente' => 'boolean',
    ];

    public function investigador(): BelongsTo
    {
        return $this->belongsTo(Investigador::class);
    }

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function funcion(): BelongsTo
    {
        return $this->belongsTo(Funcion::class);
    }
}
