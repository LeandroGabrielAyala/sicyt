<?php

namespace App\Models;

use App\Models\InvestigadorProyecto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proyecto extends Model
{

    protected $fillable = [
        'nro', 'nombre', 'resumen', 'duracion', 'inicio', 'fin', 'disposicion', 
        'resolucion', 'pdf_resolucion', 'pdf_disposicion', 'presupuesto', 'estado',
        'team_id', 'campo_id', 'objetivo_id', 'actividad_id'
    ];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    public function investigadores(): belongsToMany
    {
        return $this->belongsToMany(Investigador::class)
            ->using(InvestigadorProyecto::class, 'investigador_proyecto')
            ->withPivot([ 'vigente', 'inicio', 'fin', 'funcion_id', 'disposicion', 'resolucion', 'pdf_disposicion', 'pdf_resolucion'
            ])
            ->withTimestamps();
    }

    
    public function investigadoresProyectos(): HasMany
    {
        return $this->hasMany(InvestigadorProyecto::class, 'proyecto_id');
    }

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
