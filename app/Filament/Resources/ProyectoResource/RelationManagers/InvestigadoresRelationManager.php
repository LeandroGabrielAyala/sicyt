<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestigadoresRelationManager extends RelationManager
{
    protected static string $relationship = 'investigador';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('investigador_id')
                    ->label('Investigador')
                    ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                    ->searchable()
                    ->required()
                    ->preload(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('apellido')
            ->columns([
                Tables\Columns\TextColumn::make('apellido'),
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
