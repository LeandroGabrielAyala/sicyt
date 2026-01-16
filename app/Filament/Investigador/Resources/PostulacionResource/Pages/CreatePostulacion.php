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

        // 🔎 Buscar borrador existente
        $borrador = Postulacion::where('investigador_id', $investigador->id)
            ->where('estado', 'cargando')
            ->first();

        if (! $borrador) {
            $convocatoria = ConvocatoriaProyecto::where('estado', true)->firstOrFail();

            $borrador = Postulacion::create([
                'investigador_id' => $investigador->id,
                'convocatoria_id' => $convocatoria->id,
                'estado' => 'cargando',
            ]);
        }

        // 👉 Redirigir al EDIT del borrador
        $this->redirect(
            PostulacionResource::getUrl('edit', ['record' => $borrador])
        );
    }
}
