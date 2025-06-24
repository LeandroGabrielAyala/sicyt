<?php

namespace App\Filament\Resources\BecarioResource\RelationManagers;

use App\Models\ConvocatoriaBeca;
use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class ProyectosRelationManager extends RelationManager
{
    protected static string $relationship = 'proyectos'; // método en Becario.php

    protected static ?string $title = 'Proyectos Asociados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nro')->label('Nro. de Proyecto'),
                TextColumn::make('pivot.director_id')
                    ->label('Director Beca')
                    ->formatStateUsing(fn ($state) => Investigador::find($state)?->apellido . ', ' . Investigador::find($state)?->nombre),
                TextColumn::make('pivot.codirector_id')
                    ->label('Codirector Beca')
                    ->formatStateUsing(fn ($state) => $state ? Investigador::find($state)?->apellido . ', ' . Investigador::find($state)?->nombre : '-'),
                TextColumn::make('pivot.convocatoria_beca_id')
                    ->label('Convocatoria')
                    ->formatStateUsing(fn ($state) => ConvocatoriaBeca::find($state)?->anio),
            ])
            ->actions([
                ViewAction::make(),
                // DetachAction::make()->label('Quitar'),
            ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // no es necesario un formulario aquí
    }
}
