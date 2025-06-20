<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campo extends Model
{
    protected $fillable = ['nombre'];

    public function proyecto(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

    public function disciplina(): HasMany
    {
        return $this->hasMany(Disciplina::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
