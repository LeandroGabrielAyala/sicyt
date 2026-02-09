<?php

namespace App\Filament\Investigador\Resources\PostulacionResource\Pages;

use App\Filament\Investigador\Resources\PostulacionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulacion;
use App\Models\ConvocatoriaProyecto;

class CreatePostulacion extends CreateRecord
{
    protected static string $resource = PostulacionResource::class;

public function mount(): void
{
    parent::mount();

    $investigador = Auth::user()->investigador;
    $investigadorId = $investigador->id;

    // Convocatorias vigentes
    $convocatoriasVigentes = ConvocatoriaProyecto::where('estado', true)
        ->pluck('id');

    // Convocatorias ya usadas por este investigador
    $convocatoriasYaPostuladas = Postulacion::where('investigador_id', $investigadorId)
        ->pluck('convocatoria_id');

    // Buscar una convocatoria disponible
    $convocatoriaDisponible = ConvocatoriaProyecto::whereIn('id', $convocatoriasVigentes)
        ->whereNotIn('id', $convocatoriasYaPostuladas)
        ->firstOrFail();

    // 🔎 ¿Ya existe borrador PARA ESA CONVOCATORIA?
    $borrador = Postulacion::where('investigador_id', $investigadorId)
        ->where('convocatoria_id', $convocatoriaDisponible->id)
        ->where('estado', 'cargando')
        ->first();

    // Si no existe, lo creamos
    if (! $borrador) {
        $borrador = Postulacion::create([
            'investigador_id' => $investigadorId,
            'convocatoria_id' => $convocatoriaDisponible->id,
            'estado' => 'cargando',
        ]);
    }

    // 👉 Ir a editar ESE borrador
    $this->redirect(
        PostulacionResource::getUrl('edit', ['record' => $borrador])
    );
}


}
