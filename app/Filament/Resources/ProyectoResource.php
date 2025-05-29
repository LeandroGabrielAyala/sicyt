<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos del Proyecto')
                ->description('Datos generales del Proyecto')
                ->schema([
                    TextInput::make('nro')
                        ->required(),
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('resumen')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('duracion')
                        ->required(),
                    DatePicker::make('inicio')
                        ->required(),
                    DatePicker::make('fin')
                        ->required()
                ])->columns(2),
                Section::make('Información Adicional')
                ->description('Resolución y Estado del Proyecto')
                ->schema([
                    TextInput::make('resolucion')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('pdf_resolucion')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('presupuesto')
                        ->required(),
                    Toggle::make('Vigente / Novigente')
                        ->required(),
                    ])->columns(2),
                Section::make('Clasificación')
                ->description('Datos relevante para el RACT')
                ->schema([
                    Select::make('campo_id')
                        ->relationship('campo', 'nombre')
                        ->required(),
                    Select::make('objetivo_id')
                        ->relationship('objetivo', 'nombre')
                        ->required()
                        ->searchable()
                        ->preload(),
                        //->multiple(),
                    Select::make('actividad_id')
                        ->relationship('actividad', 'nombre')
                        ->required()
                ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nro')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                /*Tables\Columns\TextColumn::make('resumen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('campo_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('objetivo_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('actividad_id')
                    ->numeric()
                    ->sortable(),*/
                Tables\Columns\TextColumn::make('duracion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fin')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('resolucion')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pdf_resolucion')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('presupuesto')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            'view' => Pages\ViewProyecto::route('/{record}'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
