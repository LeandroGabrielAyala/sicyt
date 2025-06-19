<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Investigador;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class InvestigadorRelationManager extends RelationManager
{
    protected static string $relationship = 'investigador'; // importante que coincida con el nombre del método en Proyecto.php

    protected static ?string $title = 'Investigadores asociados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')->label('Apellido'),
                TextColumn::make('nombre')->label('Nombre'),
                TextColumn::make('dni')->label('DNI'),
                TextColumn::make('email')->label('Email'),
            ])
            ->headerActions([
                AttachAction::make()->label('Asociar'), // permite asociar investigadores existentes
            ])
            ->actions([
                DetachAction::make()->label('Quitar'), // permite desasociar
                ViewAction::make()->label('Ver'),   // opcional: ver detalles
            ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // No queremos formulario para crear investigadores nuevos desde acá
    }
}
