<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disciplina extends Model
{
    protected $fillable = ['nombre', 'campo_id'];

    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class);
    }

        public function proyecto(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }
}
