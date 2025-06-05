<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObjetivoResource\Pages;
use App\Filament\Resources\ObjetivoResource\RelationManagers;
use App\Models\Objetivo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObjetivoResource extends Resource
{
    protected static ?string $model = Objetivo::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Objetivos SocioEcon';
    protected static ?string $modelLabel = 'Objetivos Socioeconomicos';
    protected static ?string $navigationGroup = 'Configuración';
    protected static ?string $slug = 'objetivo-socioeconomico-pi';
    protected static ?int $navigationSort = 2;

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListObjetivos::route('/'),
            'create' => Pages\CreateObjetivo::route('/create'),
            'view' => Pages\ViewObjetivo::route('/{record}'),
            'edit' => Pages\EditObjetivo::route('/{record}/edit'),
        ];
    }
}
