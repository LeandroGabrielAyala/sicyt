<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConvocatoriaBeca extends Model
{
    protected $fillable = [
    'tipo_beca_id', 'anio', 'estado', 'inicio', 'fin', 'pdf_resolucion', 'pdf_disposicion'];

    protected $casts = [
        'pdf_disposicion' => 'array',
        'pdf_resolucion' => 'array',
    ];

    public function tipoBeca()
    {
        return $this->belongsTo(TipoBeca::class, 'tipo_beca_id');
    }
}
