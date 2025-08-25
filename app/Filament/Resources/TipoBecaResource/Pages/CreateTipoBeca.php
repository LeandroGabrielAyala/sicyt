<?php

namespace App\Filament\Resources\TipoBecaResource\Pages;

use App\Filament\Resources\TipoBecaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class CreateTipoBeca extends CreateRecord
{
    protected static string $resource = TipoBecaResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tipo de Beca Creado')
            ->body('¡El Tipo de Beca fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Tipo de Beca';
    }

    public function getBreadcrumb(): string
    {
        return 'Crear nuevo';
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('guardar')
                ->label('Guardar')
                ->submit('create')
                ->successRedirectUrl(
                    TipoBecaResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
