<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConvocatoriaBeca extends Model
{
    protected $fillable = [
    'tipo_beca_id', 'anio', 'estado', 'inicio', 'fin', 'disposicion', 'resolucion', 'pdf_resolucion', 'pdf_disposicion'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    public function tipoBeca(): BelongsTo
    {
        return $this->belongsTo(TipoBeca::class, 'tipo_beca_id');
    }

    // App\Models\ConvocatoriaBeca.php

    public function getDescripcionAttribute()
    {
        return 'Convocatoria ' . $this->anio . ' (' . ($this->tipoBeca->nombre ?? '-') . ')';
    }

    public function becarios()
    {
        return $this->belongsToMany(Becario::class, 'becario_proyecto')
            ->withPivot(['tipo_beca', 'vigente', 'director_id', 'codirector_id'])
            ->withTimestamps();
    }

}
