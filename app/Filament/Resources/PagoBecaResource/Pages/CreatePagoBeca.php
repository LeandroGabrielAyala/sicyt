<?php

namespace App\Filament\Resources\PagoBecaResource\Pages;

use App\Filament\Resources\PagoBecaResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePagoBeca extends CreateRecord
{
    protected static string $resource = PagoBecaResource::class;

    // Para guardar los datos del repeater manualmente
    protected array $becarios = [];

    // Intercepta los datos del formulario antes de crear el PagoBeca
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Guardamos los datos del Repeater en una variable y los sacamos del $data
        $this->becarios = $data['becarios'] ?? [];
        unset($data['becarios']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $becarios = $this->form->getState()['becarios'] ?? [];

        foreach ($becarios as $becarioData) {
            $this->record->becarios()->attach($becarioData['becario_id'], [
                'monto' => $becarioData['monto'],
            ]);
        }
    }


}
