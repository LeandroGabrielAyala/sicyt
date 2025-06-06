<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proyecto extends Model
{
    protected $fillable = ['nro', 'nombre', 'resumen', 'duracion', 'inicio', 'fin', 'disposicion', 'resolucion', 'pdf_resolucion', 'pdf_disposicion', 'presupuesto', 'estado', 'campo_id', 'objetivo_id', 'actividad_id'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

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

    protected static function booted()
    {
        static::saving(function ($proyecto) {
            $hoy = Carbon::now()->startOfDay();
            $fin = $proyecto->fin ? Carbon::parse($proyecto->fin)->startOfDay() : null;

            $finChanged = $proyecto->isDirty('fin');
            $estadoChanged = $proyecto->isDirty('estado');

            if ($finChanged) {
                if (!$fin || $fin->lt($hoy)) {
                    $proyecto->estado = 0;
                    $proyecto->fin = $fin;
                } else if (!$fin || $fin->gte($hoy)) {
                    $proyecto->estado = 1;
                }
            }

            if ($estadoChanged) {
                if ($proyecto->estado == 0) {
                    // Estado cambiado a no vigente → fecha fin = hoy
                    // $proyecto->fin = $hoy->toDateString();
                } else if ($proyecto->estado == 1) {
                    $proyecto->fin = $fin;
                }
                // Si el estado es 1, no tocar la fecha fin (la respeta)
            }
        });
    }
}
