<?php

namespace App\Filament\Resources\ConvocatoriaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BecariosRelationManager extends RelationManager
{
    protected static string $relationship = 'Becarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre_completo')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre_completo')
            ->columns([
                TextColumn::make('nombre')->label('Nombre'),
                TextColumn::make('apellido')->label('Apellido'),
                TextColumn::make('pivot.tipo_beca')->label('Tipo de Beca'),
                TextColumn::make('pivot.vigente')->label('Estado')->formatStateUsing(
                    fn ($state) => $state ? 'Vigente' : 'No vigente'
                ),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
