<?php

namespace App\Models;

use App\Models\Campo;
use App\Models\Carrera;
use App\Models\Disciplina;
use App\Models\Objetivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Becario extends Model
{
    protected $fillable = [
        'nombre', 'apellido', 'dni', 'cuil', 'fecha_nac', 'lugar_nac', 'domicilio', 'provincia', 'email', 'telefono', 'nivel_academico_id', 'disciplina_id', 'campo_id', 'objetivo_id', 'titulo', 'carrera_id', 'plan_trabajo'
    ];

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'becario_proyecto')
            ->using(\App\Models\BecarioProyecto::class)
            ->withPivot([
                'director_id',
                'codirector_id',
                'convocatoria_beca_id',  // ya estaba
                'tipo_beca',
                'vigente',
                'plan_trabajo',          // <-- Agregar este campo aquí
            ])
            ->withTimestamps();
    }



    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function nivelAcademico(): BelongsTo
    {
        return $this->belongsTo(NivelAcademico::class);
    }

    public function disciplina(): BelongsTo
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class);
    }

    public function objetivo(): BelongsTo
    {
        return $this->belongsTo(Objetivo::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->apellido}, {$this->nombre}";
    }

    public function convocatorias()
    {
        return $this->belongsToMany(ConvocatoriaBeca::class, 'becario_proyecto')
            ->withPivot(['tipo_beca', 'vigente', 'director_id', 'codirector_id'])
            ->withTimestamps();
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(BecarioPagoBeca::class);
    }


}
