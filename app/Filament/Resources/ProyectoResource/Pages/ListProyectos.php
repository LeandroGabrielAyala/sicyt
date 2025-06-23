<?php

namespace App\Filament\Resources\ProyectoResource\Pages;

use App\Filament\Resources\ProyectoResource;
use App\Models\Proyecto;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListProyectos extends ListRecords
{
    protected static string $resource = ProyectoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nuevo Proyecto'),
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de P.I. UNCAUS';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.proyectos-de-investigacion.index') => 'Proyectos de Investigación',
            'Todos',
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Vigente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where(function ($query) {
                        $query->whereDate('fin', '>=', today())
                            ->where('estado', 1);
                    })
                )
                ->badge(
                    Proyecto::query()
                    ->where(function ($query) {
                    $query->whereDate('fin', '>=', today())
                        ->where('estado', 1);
                })
                ->count()
            ),
            'No Vigente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where(function ($query) {
                        $query->whereDate('fin', '<', today())
                            ->orWhere('estado', 0);
                    })
                )
                ->badge(
                    Proyecto::query()
                    ->where(function ($query) {
                    $query->whereDate('fin', '<', today())
                        ->orWhere('estado', 0);
                })
                ->count()
            ),
        ];
    }
}
