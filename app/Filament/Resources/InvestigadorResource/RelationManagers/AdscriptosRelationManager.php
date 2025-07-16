<?php

namespace App\Filament\Resources\InvestigadorResource\RelationManagers;

use App\Models\AdscriptoProyecto;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class AdscriptosRelationManager extends RelationManager
{
    protected static string $relationship = 'adscriptosComoDirector'; // no se usa en este caso, ya que query es manual
    protected static ?string $title = 'Adscriptos a Cargo';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->heading('Adscriptos a cargo del Director/Co-director.')
            ->query(
                fn (): Builder => AdscriptoProyecto::query()
                    ->where(function ($query) {
                        $query->where('director_id', $this->ownerRecord->id)
                              ->orWhere('codirector_id', $this->ownerRecord->id);
                    })
                    ->with(['adscripto', 'proyecto', 'convocatoria'])
            )
            ->columns([
                TextColumn::make('adscripto.apellido')->label('Apellido'),
                TextColumn::make('adscripto.nombre')->label('Nombre'),
                TextColumn::make('proyecto.nro')->label('Proyecto'),
                TextColumn::make('convocatoria.anio')->label('Convocatoria'),
                TextColumn::make('vigente')->label('Vigente')
                    ->formatStateUsing(fn ($state) => $state ? '✔️' : '❌'),
                TextColumn::make('funcion')->label('Función')->state(function ($record) {
                    return $record->director_id === $this->ownerRecord->id ? 'Director' : 'Codirector';
                }),
            ]);
    }
}
