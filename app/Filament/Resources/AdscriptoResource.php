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
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Actions\ViewAction;
use App\Filament\Exports\AdscriptoExporter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AdscriptoResource extends Resource
{
    protected static ?string $model = Adscripto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Adscriptos';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Adscriptos';
    protected static ?string $slug = 'adscriptos';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'apellido_nombre';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "{$record->apellido}, {$record->nombre}";
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['apellido', 'nombre', 'dni', 'email', 'telefono'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'DNI' => $record->dni,
            'Email' => $record->email,
            'Teléfono' => $record->telefono,
            'Carrera' => optional($record->carrera)->nombre ?? '—',
            'Título' => optional($record->titulo)->titulo ?? '—',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['carrera', 'titulo']);
    }

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
                                                    $titulo = \App\Models\Carrera::find($state)?->titulo;
                                                    $set('titulo', $titulo); // Cargás el texto del título en el campo del modelo Becario
                                                })
                                                ->required(),

                                            TextInput::make('titulo')
                                                ->label('Título profesional')
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
                TextColumn::make('telefono')->label('Teléfono'),
            ])->defaultSort('apellido', 'asc') // 👈 Orden alfabético por defecto
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->color('primary')
                    ->modalHeading(fn ($record) => 'Detalles del Adscripto ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')

                    // ✅ Aquí cargás relaciones necesarias
                    ->record(fn ($record) => $record->load(['titulo', 'carrera', 'proyectos']))

                    ->infolist(fn (ViewAction $action): array => [
                        InfoTabs::make('Tabs')->tabs([

                            InfoTab::make('Datos Personales')->schema([
                                Section::make('Datos personales')->schema([
                                    TextEntry::make('nombre')->label('Nombre(s)')->color('gray'),
                                    TextEntry::make('apellido')->label('Apellido(s)')->color('gray'),
                                    TextEntry::make('dni')->label('DNI')->color('gray'),
                                    TextEntry::make('cuil')->label('CUIL')->color('gray'),
                                    TextEntry::make('fecha_nac')->label('Fecha de nacimiento')->color('gray')->date(),
                                    TextEntry::make('lugar_nac')->label('Lugar de nacimiento')->color('gray'),
                                    TextEntry::make('domicilio')->label('Domicilio')->color('gray'),
                                    TextEntry::make('provincia')->label('Provincia')->color('gray'),
                                    TextEntry::make('codigo')->label('Código Postal')->color('gray'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Proyecto')->schema([
                                Section::make('')->schema([
                                    Entry::make('proyectos')
                                        ->label('Proyectos Asociados')
                                        ->view('livewire.proyectos-adscriptos-list', [
                                            'adscripto' => $action->getRecord(),
                                        ]),
                                ]),
                            ]),

                            InfoTab::make('Contacto')->schema([
                                Section::make('Datos de contacto')->schema([
                                    TextEntry::make('email')->label('Correo electrónico')->color('gray'),
                                    TextEntry::make('telefono')->label('Teléfono')->color('gray'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Formación')->schema([
                                Section::make('Formación académica')->schema([
                                    TextEntry::make('carrera.nombre')->label('Carrera')->color('gray'),
                                    TextEntry::make('titulo.titulo')->label('Título')->color('gray'),
                                ])->columns(2),
                            ]),
                        ]),
                    ]),


                Tables\Actions\EditAction::make()->label('')->color('primary'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(AdscriptoExporter::class), // Solo seleccionados
                    DeleteBulkAction::make(),
                ])
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
