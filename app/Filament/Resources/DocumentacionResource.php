<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentacionResource\Pages;
use App\Filament\Resources\DocumentacionResource\RelationManagers;
use App\Models\Documentacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentacionResource extends Resource
{
    protected static ?string $model = Documentacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Documentación';
    protected static ?string $navigationGroup = 'Documentación';
    protected static ?string $modelLabel = 'Documentación';
    protected static ?string $slug = 'documentacion';

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
            'index' => Pages\ListDocumentacions::route('/'),
            'create' => Pages\CreateDocumentacion::route('/create'),
            'edit' => Pages\EditDocumentacion::route('/{record}/edit'),
        ];
    }
}
