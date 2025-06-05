<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampoResource\RelationManagers\ProyectoRelationManager;
use App\Filament\Resources\CampoResource\Pages;
use App\Filament\Resources\CampoResource\RelationManagers;
use App\Models\Campo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampoResource extends Resource
{
    protected static ?string $model = Campo::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Campo de Aplicación';
    protected static ?string $modelLabel = 'Campo de Aplicación';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $slug = 'campo-de-aplicacion-pi';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProyectoRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampos::route('/'),
            'create' => Pages\CreateCampo::route('/create'),
            'view' => Pages\ViewCampo::route('/{record}'),
            'edit' => Pages\EditCampo::route('/{record}/edit'),
        ];
    }
}
