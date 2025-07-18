<?php

namespace App\Filament\Resources\AdscriptoResource\Pages;

use App\Filament\Resources\AdscriptoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;

class CreateAdscripto extends CreateRecord
{
    protected static string $resource = AdscriptoResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Adscripto Creado')
            ->body('¡El adscripto fue creado exitosamente!');
    }

    public function getTitle(): string
    {
        return 'Crear nuevo Adscripto';
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
                    AdscriptoResource::getUrl('index')
                ),

            Action::make('cancelar')
                ->label('Cancelar')
                ->url($this->getResource()::getUrl())
                ->color('gray'),
        ];
    }

    /*protected function getCreatedNotificationTitle(): ?string
    {
        return 'Adscripto Creado';
    }*/
}
