<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Becario extends Model
{
    protected $fillable = ['nombre', 'apellido', 'dni', 'cuil', 'fecha_nac', 'domicilio', 'provincia', 'email', 'telefono', 'nivel_academico_id', 'disciplina_id', 'campo_id', 'objetivo_id', 'titulo', 'carrera', 'tipo_beca_id', 'plan_trabajo', 'pago'];

    public function tipo_beca(): BelongsTo
    {
        return $this->belongsTo(TipoBeca::class);
    }

}
