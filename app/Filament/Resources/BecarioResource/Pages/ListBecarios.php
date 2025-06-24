<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use App\Models\Becario;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;



class ListBecarios extends ListRecords
{
    protected static string $resource = BecarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Becario'),
        ];
    }

    public function getTitle(): string
    {
        return 'Lista de Becarios';
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Todos')
                ->badge(Becario::count()),

            Tab::make('UNCAUS Grado')
                ->label('UN Grado')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Grado'))
                )
                ->badge(Becario::whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Grado'))->count()),

            Tab::make('UNCAUS Posgrado')
                ->label('UN Posgrado')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Posgrado'))
                )
                ->badge(Becario::whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Posgrado'))->count()),

            Tab::make('CIN')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'CIN'))
                )
                ->badge(Becario::whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'CIN'))->count()),
        ];
    }


}
