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
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Todos' => Tab::make(),
            'Vigente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('fin', '>=', today()))
                ->badge(Proyecto::query()->whereDate('fin', '>=', today())->count()
            ),
            'No Vigente' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('fin', '<', today()))
                ->badge(Proyecto::query()->whereDate('fin', '<', today())->count()
            )
        ];
    }
}
