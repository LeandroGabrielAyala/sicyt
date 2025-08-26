<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsignacionResource\Pages;
use App\Models\Asignacion;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AsignacionResource extends Resource
{
    protected static ?string $model = Asignacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Asignación';
    protected static ?string $modelLabel = 'Asignación';
    protected static ?string $navigationGroup = 'Configuración CyR';
    protected static ?string $slug = 'asignacion';
    protected static ?int $navigationSort = 1;

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
            'index' => Pages\ListAsignacions::route('/'),
            'create' => Pages\CreateAsignacion::route('/create'),
            'edit' => Pages\EditAsignacion::route('/{record}/edit'),
        ];
    }
}
