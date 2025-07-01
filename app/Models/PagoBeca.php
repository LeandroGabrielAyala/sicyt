<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PagoBeca extends Model
{
    protected $table = 'pagos_becas';

    protected $fillable = [
        'anio',
        'mes',
        'tipo_beca',
        'convocatoria_beca_id',
    ];

    // Relaciones

    // public function tipoBeca(): BelongsTo
    // {
    //     return $this->belongsTo(TipoBeca::class);
    // }

    public function convocatoriaBeca(): BelongsTo
    {
        return $this->belongsTo(ConvocatoriaBeca::class);
    }

    // Relación con la tabla pivote para manejar monto pagado
    public function becariosPivot(): HasMany
    {
        return $this->hasMany(BecarioPagoBeca::class);
    }

    // Relación directa a los becarios (modelo), con monto en pivot
    public function becarios(): BelongsToMany
    {
        return $this->belongsToMany(Becario::class, 'becario_pago_beca')
            ->withPivot('monto')
            ->withTimestamps();
    }

    // Accesor para obtener la suma total de montos pagados
    public function getBecariosSumMontoAttribute(): float
    {
        return $this->becariosPivot()->sum('monto');
    }
}
