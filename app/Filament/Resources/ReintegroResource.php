<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReintegroResource\Pages;
use App\Filament\Resources\ReintegroResource\RelationManagers;
use App\Models\Reintegro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReintegroResource extends Resource
{
    protected static ?string $model = Reintegro::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Reintegros';
    protected static ?string $navigationGroup = 'Compras y Reintegros';
    protected static ?string $modelLabel = 'Reintegros';
    protected static ?string $slug = 'reintegros-proyectos';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary'; //return static::getModel()::count() > 5 ? 'primary' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListReintegros::route('/'),
            'create' => Pages\CreateReintegro::route('/create'),
            'edit' => Pages\EditReintegro::route('/{record}/edit'),
        ];
    }
}
