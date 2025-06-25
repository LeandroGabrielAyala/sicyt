<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Becario;
use App\Models\Investigador;
use App\Models\ConvocatoriaBeca;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DetachAction;

class BecariosRelationManager extends RelationManager
{
    protected static string $relationship = 'Becarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')
                    ->label('Nombre completo')
                    ->formatStateUsing(fn ($state, $record) => $record->apellido . ', ' . $record->nombre),
                TextColumn::make('pivot.director_id')
                    ->label('Director')
                    ->formatStateUsing(fn ($state) => \App\Models\Investigador::find($state)?->apellido . ', ' . \App\Models\Investigador::find($state)?->nombre),
                TextColumn::make('pivot.codirector_id')
                    ->label('Codirector')
                    ->formatStateUsing(fn ($state) => $state ? \App\Models\Investigador::find($state)?->apellido . ', ' . \App\Models\Investigador::find($state)?->nombre : '-'),
                TextColumn::make('pivot.tipo_beca')
                    ->label('Tipo de Beca')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Grado' => 'success',
                        'Posgrado' => 'info',
                        'CIN' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('pivot.convocatoria.tipoBeca.nombre')
                    ->label('Tipo de Convocatoria')
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('pivot.convocatoria.anio')->label('Convocatoria'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->form([
                        Grid::make(2)->schema([
                            Select::make('recordId')
                                ->label('Becario')
                                ->options(Becario::all()->pluck('nombre_completo', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('director_id')
                                ->label('Director de la Beca')
                                ->options(function (callable $get) {
                                    $codirectorId = $get('codirector_id');
                                    return Investigador::when($codirectorId, function ($query) use ($codirectorId) {
                                        $query->where('id', '!=', $codirectorId);
                                    })->get()->pluck('nombre_completo', 'id');
                                })
                                ->searchable()
                                ->reactive()
                                ->required(),
                            Select::make('codirector_id')
                                ->label('Codirector de la Beca')
                                ->options(function (callable $get) {
                                    $directorId = $get('director_id');
                                    return Investigador::when($directorId, function ($query) use ($directorId) {
                                        $query->where('id', '!=', $directorId);
                                    })->get()->pluck('nombre_completo', 'id');
                                })
                                ->searchable()
                                ->reactive(),
                            Select::make('tipo_beca_convocatoria')
                                ->label('Tipo de Convocatoria')
                                ->options(
                                    \App\Models\TipoBeca::all()->pluck('nombre', 'id')
                                )
                                ->searchable()
                                ->required(),
                            Select::make('convocatoria_beca_id')
                                ->label('Convocatoria')
                                ->options(ConvocatoriaBeca::all()->pluck('anio', 'id'))
                                ->required(),
                            Select::make('tipo_beca')
                                ->label('Tipo de Beca')
                                ->options(\App\Models\BecarioProyecto::tiposBeca())
                                ->required(),
                            // Nuevo campo: vigente
                            Toggle::make('vigente')
                                ->label('Vigente / No Vigente')
                                ->default(true),
                        ]),
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                DetachAction::make(),
            ]);
    }
}
