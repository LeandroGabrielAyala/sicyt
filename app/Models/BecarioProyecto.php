<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BecarioProyecto extends Pivot
{
    protected $table = 'becario_proyecto';

    protected $fillable = [
        'becario_id',
        'proyecto_id',
        'director_id',
        'codirector_id',
        'convocatoria_beca_id',
        'plan_trabajo',
        'tipo_beca_convocatoria',
        'tipo_beca',   // agregado
        'vigente',
    ];

    protected $casts = [
        'vigente' => 'boolean',
    ];

    public function becario()
    {
        return $this->belongsTo(Becario::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function director()
    {
        return $this->belongsTo(Investigador::class, 'director_id');
    }

    public function codirector()
    {
        return $this->belongsTo(Investigador::class, 'codirector_id');
    }

    public function convocatoria()
    {
        return $this->belongsTo(ConvocatoriaBeca::class, 'convocatoria_beca_id');
    }


    // Opciones para el campo tipo_beca (enum)
    public static function tiposBeca(): array
    {
        return [
            'Grado' => 'Grado',
            'Posgrado' => 'Posgrado',
            'CIN' => 'CIN',
        ];
    }
}
