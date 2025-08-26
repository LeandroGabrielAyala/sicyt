<?php

namespace App\Filament\Resources\RubroResource\Pages;

use App\Filament\Resources\RubroResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRubro extends CreateRecord
{
    protected static string $resource = RubroResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Rubro Creado')
            ->body('¡La Rubro fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Rubro';
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
                    RubroResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }
}
