<?php

namespace App\Filament\Resources\PostulacionResource\Pages;

use App\Filament\Resources\PostulacionResource;
use Filament\Actions;
use App\Models\Postulacion;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListPostulacions extends ListRecords
{
    protected static string $resource = PostulacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = Postulacion::query()
            ->where('estado', '!=', 'cargando');

        return [
            'Todos' => Tab::make()
                ->badge($baseQuery->count()),

            'Aprobados' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('estado', 'aprobado')
                )
                ->badge(
                    (clone $baseQuery)->where('estado', 'aprobado')->count()
                )
                ->badgeColor('success'),

            'Pendientes' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('estado', 'pendiente')
                )
                ->badge(
                    (clone $baseQuery)->where('estado', 'pendiente')->count()
                )
                ->badgeColor('warning'),

            'Rechazados' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('estado', 'rechazado')
                )
                ->badge(
                    (clone $baseQuery)->where('estado', 'rechazado')->count()
                )
                ->badgeColor('danger'),
        ];
    }


}
