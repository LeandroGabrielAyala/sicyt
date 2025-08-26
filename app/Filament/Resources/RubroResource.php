<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RubroResource\Pages;
use App\Filament\Resources\RubroResource\RelationManagers;
use App\Models\Rubro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class RubroResource extends Resource
{
    protected static ?string $model = Rubro::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Rubro';
    protected static ?string $modelLabel = 'Rubro';
    protected static ?string $navigationGroup = 'Configuración CyR';
    protected static ?string $slug = 'rubro';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Borrar'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListRubros::route('/'),
            'create' => Pages\CreateRubro::route('/create'),
            'edit' => Pages\EditRubro::route('/{record}/edit'),
        ];
    }
}
