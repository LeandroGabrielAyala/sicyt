<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BecarioPagoBeca extends Model
{
    protected $table = 'becario_pago_beca';

    protected $fillable = [
        'pago_beca_id',
        'becario_id',
        'monto',
    ];

    protected $casts = [
        'monto' => 'float',
    ];

    // Relaciones

    public function pago(): BelongsTo
    {
        return $this->belongsTo(PagoBeca::class, 'pago_beca_id');
    }

    public function becario(): BelongsTo
    {
        return $this->belongsTo(Becario::class);
    }
}
