<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    protected $fillable = ['nro', 'nombre', 'resumen', 'duracion', 'inicio', 'fin', 'resolucion', 'pdf_resolucion', 'presupuesto', 'estado', 'campo_id', 'objetivo_id', 'actividad_id'];

    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class);
    }

    public function objetivo(): BelongsTo
    {
        return $this->belongsTo(Objetivo::class);
    }

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }
}
