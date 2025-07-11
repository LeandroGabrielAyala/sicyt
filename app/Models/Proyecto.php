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
        'carrera_id', 'campo_id', 'objetivo_id', 'actividad_id'
    ];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function investigador(): BelongsToMany
    {
        return $this->belongsToMany(Investigador::class)
            ->using(InvestigadorProyecto::class)
            ->withPivot([
                'funcion_id', 'inicio', 'fin', 'pdf_disposicion', 'pdf_resolucion', 'vigente',
            ])
            ->withTimestamps();
    }

    public function investigadorDirector()
    {
        return $this->belongsToMany(Investigador::class, 'investigador_proyecto')
            ->withPivot('funcion_id')
            ->wherePivot('funcion_id', 1);
    }

    public function investigadorCodirector()
    {
        return $this->belongsToMany(Investigador::class, 'investigador_proyecto')
            ->withPivot('funcion_id')
            ->wherePivot('funcion_id', [2, 6]);
    }

    public function director()
    {
        return $this->investigadores()?->firstWhere('pivot.funcion', 'director');
    }

    public function codirector()
    {
        return $this->investigadores()?->firstWhere('pivot.funcion', 'codirector');
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function becarios()
    {
        return $this->belongsToMany(Becario::class, 'becario_proyecto')
            ->using(\App\Models\BecarioProyecto::class)
            ->withPivot([
                'director_id',
                'codirector_id',
                'convocatoria_beca_id',
                'tipo_beca',
                'vigente',
            ])
            ->withTimestamps();
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

    public function adscriptos()
    {
        return $this->belongsToMany(Adscripto::class, 'adscripto_proyecto')
            ->withPivot([
                'director_id',
                'codirector_id',
                'convocatoria_adscripto_id',
                'vigente',
            ])
            ->withTimestamps();
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
