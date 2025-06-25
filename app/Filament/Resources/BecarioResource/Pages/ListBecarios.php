<?php

namespace App\Filament\Resources\BecarioResource\Pages;

use App\Filament\Resources\BecarioResource;
use App\Models\Becario;
use Filament\Actions\CreateAction;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\Page;



class ListBecarios extends ListRecords
{
    protected static string $resource = BecarioResource::class;

    public ?string $activeTab = 'Todos'; // Por defecto Todos

    protected function getTableQuery(): Builder
    {
        return Becario::query()
            ->join('becario_proyecto', 'becarios.id', '=', 'becario_proyecto.becario_id')
            ->select('becarios.*', 'becario_proyecto.tipo_beca');
    }



    public function getTabs(): array
    {
        return [
            Tab::make('Todos')
                ->badge(Becario::count()),

            Tab::make('Grado')
                ->label('UN Grado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('becario_proyecto.tipo_beca', 'Grado'))
                ->badge(DB::table('becario_proyecto')->where('tipo_beca', 'Grado')->count()),

            Tab::make('Posgrado')
                ->label('UN Posgrado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('becario_proyecto.tipo_beca', 'Posgrado'))
                ->badge(DB::table('becario_proyecto')->where('tipo_beca', 'Posgrado')->count()),

            Tab::make('CIN')
                ->label('CIN')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('becario_proyecto.tipo_beca', 'CIN'))
                ->badge(DB::table('becario_proyecto')->where('tipo_beca', 'CIN')->count()),
        ];
    }

}