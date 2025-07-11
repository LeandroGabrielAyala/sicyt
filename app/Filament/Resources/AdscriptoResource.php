<?php

namespace App\Filament\Resources;

use App\Models\Carrera;
use App\Filament\Resources\AdscriptoResource\Pages;
use App\Filament\Resources\AdscriptoResource\RelationManagers;
use App\Models\Adscripto;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Forms\Components\Tabs as FormTabs;
use Filament\Forms\Components\Tabs\Tab as FormTab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdscriptoResource extends Resource
{
    protected static ?string $model = Adscripto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Adscriptos';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Adscriptos';
    protected static ?string $slug = 'adscriptos';
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
                FormTabs::make('Contenido')
                    ->tabs([
                        FormTab::make('Datos personales')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        TextInput::make('nombre')->required()->label('Nombre(s)'),
                                        TextInput::make('apellido')->required()->label('Apellido(s)'),
                                        TextInput::make('dni')->required()->label('DNI')->maxLength(10)->unique(ignoreRecord: true),
                                        TextInput::make('cuil')->required()->label('CUIL')->maxLength(15)->unique(ignoreRecord: true),
                                        TextInput::make('domicilio')->required()->label('Domicilio'),
                                        TextInput::make('provincia')->required()->label('Provincia'),
                                        TextInput::make('email')->email()->required()->label('Email')->unique(ignoreRecord: true),
                                        TextInput::make('telefono')->required()->maxLength(20)->label('Teléfono')->unique(ignoreRecord: true),
                                        DatePicker::make('fecha_nac')->required()->label('Fecha de nacimiento')->columnSpanFull(),
                                        TextInput::make('lugar_nac')->required()->label('Lugar de Nacimiento'),
                                        TextInput::make('codigo')->required()->label('Código Postal'),
                                    ]),
                            ]),

                        FormTab::make('Formación académica')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Select::make('carrera_id')
                                            ->label('Carrera')
                                            ->relationship('carrera', 'nombre')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $tituloId = Carrera::find($state)?->id; // o ->titulo si es texto
                                                $set('titulo_id', $tituloId);
                                            })
                                            ->required(),

                                        Select::make('titulo_id')
                                            ->label('Título')
                                            ->relationship('titulo', 'titulo')
                                            ->disabled()
                                            ->required(),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')->label('Apellido(s)')->searchable()->limit(50),
                TextColumn::make('nombre')->label('Nombre(s)')->searchable()->limit(50),
                TextColumn::make('dni')->label('DNI'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('telefono')->label('Teléfono')->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAdscriptos::route('/'),
            'create' => Pages\CreateAdscripto::route('/create'),
            'edit' => Pages\EditAdscripto::route('/{record}/edit'),
        ];
    }
}
