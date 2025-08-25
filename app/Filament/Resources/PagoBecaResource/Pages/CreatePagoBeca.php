<?php

namespace App\Filament\Resources\PagoBecaResource\Pages;

use App\Filament\Resources\PagoBecaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

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

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Pago a Becarios creado')
            ->body('¡La lista de pago fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nueva lista de pago';
    }

    public function getBreadcrumb(): string
    {
        return 'Crear nueva';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar')
                ->submit('create')
                ->successRedirectUrl(
                    PagoBecaResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }

}
