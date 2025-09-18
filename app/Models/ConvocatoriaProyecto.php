<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConvocatoriaProyecto extends Model
{
    protected $fillable = [
    'tipo_proyecto_id', 'anio', 'estado', 'inicio', 'fin', 'disposicion', 'resolucion', 'pdf_resolucion', 'pdf_disposicion'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    public function tipoProyecto(): BelongsTo
    {
        return $this->belongsTo(TipoProyecto::class, 'tipo_proyecto_id');
    }

    public function getDescripcionAttribute()
    {
        return 'Convocatoria ' . $this->anio . ' (' . ($this->tipoProyecto->nombre ?? '-') . ')';
    }

    public function getNombreCompletoAttribute()
    {
        return ($this->tipoProyecto->nombre ?? 'Sin tipo') . ' - ' . $this->anio;
    }

}
