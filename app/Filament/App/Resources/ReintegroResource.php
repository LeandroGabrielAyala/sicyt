<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ReintegroResource\Pages;
use App\Filament\App\Resources\ReintegroResource\RelationManagers;
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
            'index' => Pages\ListReintegros::route('/'),
            'create' => Pages\CreateReintegro::route('/create'),
            'edit' => Pages\EditReintegro::route('/{record}/edit'),
        ];
    }
}
