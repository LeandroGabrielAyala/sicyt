<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BecarioResource\Pages;
use App\Filament\App\Resources\BecarioResource\RelationManagers;
use App\Models\Becario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BecarioResource extends Resource
{
    protected static ?string $model = Becario::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListBecarios::route('/'),
            'create' => Pages\CreateBecario::route('/create'),
            'edit' => Pages\EditBecario::route('/{record}/edit'),
        ];
    }
}
