<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug'];

    public function proyectos(): HasMany
    {
        return $this->hasMany(Proyecto::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function getNameAttribute(): string
    {
        return $this->nombre;
    }

    public function getFilamentName(): string
    {
        return $this->nombre ?? 'Sin nombre';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->firstOrFail();
    }
}
