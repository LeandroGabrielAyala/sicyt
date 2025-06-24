<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;



class ListBecarios extends ListRecords
{
    protected static string $resource = BecarioResource::class;

    public function getTabs(): array
    {
        return [
            Tab::make('Todos'),
            Tab::make('UNCAUS Grado')->modifyQueryUsing(fn (Builder $query) =>
                $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Grado'))
            ),
            Tab::make('UNCAUS Posgrado')->modifyQueryUsing(fn (Builder $query) =>
                $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'UNCAUS Posgrado'))
            ),
            Tab::make('CIN')->modifyQueryUsing(fn (Builder $query) =>
                $query->whereHas('tipo_beca', fn ($q) => $q->where('nombre', 'CIN'))
            ),
        ];
    }


}
