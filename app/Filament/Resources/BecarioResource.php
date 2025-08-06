<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BecarioResource\Pages;
use App\Filament\Resources\BecarioResource\RelationManagers;
use App\Filament\Resources\BecarioResource\RelationManagers\ConvocatoriasRelationManager;
use App\Filament\Resources\BecarioResource\RelationManagers\ProyectosRelationManager;
use App\Models\Becario;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Forms\Components\Tabs as FormTabs;
use Filament\Forms\Components\Tabs\Tab as FormTab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Exports\BecarioExporter;
use App\Models\Carrera;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BecarioResource extends Resource
{
    protected static ?string $model = Becario::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Becarios';
    protected static ?string $navigationGroup = 'Becas';
    protected static ?string $modelLabel = 'Becarios';
    protected static ?string $slug = 'becarios';
    protected static ?int $navigationSort = 1;

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
            'Carrera' => $record->carrera?->nombre ?? '—',
            'Título' => $record->titulo ?? '—',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['carrera']);
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
                                        TextInput::make('lugar_nac')->required()->label('Lugar de Nacimiento')
                                    ]),
                            ]),

                        FormTab::make('Formación académica')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        // Select::make('carrera_id')->relationship('carrera', 'nombre'),

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

                                        Select::make('nivel_academico_id')->relationship('nivelAcademico', 'nombre'),
                                        Select::make('disciplina_id')->relationship('disciplina', 'nombre'),
                                        Select::make('campo_id')->relationship('campo', 'nombre'),
                                        Select::make('objetivo_id')->relationship('objetivo', 'nombre'),
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
                TextColumn::make('tipo_beca')
                    ->label('Tipo de Beca')
                    ->getStateUsing(fn ($record) => $record->tipo_beca)
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Grado' => 'success',
                        'Posgrado' => 'info',
                        'CIN' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => $state ?? '—'),
                TextColumn::make('convocatoria')
                    ->label('Convocatoria (Año)')
                    ->getStateUsing(function ($record) {
                        return $record->proyectos
                            ->map(function ($proyecto) {
                                $tipo = optional($proyecto->pivot->convocatoria?->tipoBeca)->nombre;
                                $anio = $proyecto->pivot->convocatoria?->anio;
                                return $tipo && $anio ? "$tipo ($anio)" : null;
                            })
                            ->filter()
                            ->unique()
                            ->implode(', ');
                    })
                    ->formatStateUsing(fn ($state) => $state ?: '—')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('telefono')->label('Teléfono')->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('apellido', 'asc') // 👈 Orden alfabético por defecto
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->modalHeading(fn ($record) => 'Detalles del Becario ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->color('primary')
                    ->infolist(fn (ViewAction $action): array => [
                        InfoTabs::make('Tabs')->tabs([

                            InfoTab::make('Proyectos')->schema([
                                Section::make('Proyectos Asociados')->schema([
                                    Entry::make('proyectos')
                                        ->label('Proyectos Asociados')
                                        ->view('livewire.proyectos-becarios-list', [
                                            'becario' => $action->getRecord(),
                                        ]),
                                ]),
                            ]),

                            InfoTab::make('Datos Personales')->schema([
                                Section::make('Datos personales del Becario')->schema([
                                    TextEntry::make('nombre')->label('Nombre(s)')->color('customgray'),
                                    TextEntry::make('apellido')->label('Apellido(s)')->color('customgray'),
                                    TextEntry::make('dni')->label('DNI')->color('customgray'),
                                    TextEntry::make('cuil')->label('CUIL')->color('customgray'),
                                    TextEntry::make('fecha_nac')->label('Fecha de Nacimiento')->color('customgray')->date(),
                                    TextEntry::make('domicilio')->label('Domicilio')->color('customgray'),
                                    TextEntry::make('provincia')->label('Provincia')->color('customgray'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Contacto')->schema([
                                Section::make('Datos de Contacto')->schema([
                                    TextEntry::make('email')->label('Correo electrónico')->color('customgray'),
                                    TextEntry::make('telefono')->label('Teléfono')->color('customgray'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Datos Académicos')->schema([
                                Section::make('Formación del Becario')->schema([
                                    TextEntry::make('titulo')->label('Título profesional')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('carrera.nombre')->label('Carrera')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Grado')
                                        ->color('customgray'),

                                    TextEntry::make('nivelAcademico.nombre')->label('Nivel Académico')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('disciplina.nombre')->label('Disciplina')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),
                                ])->columns(2),
                            ]),

                        ])
                    ]),
                EditAction::make()->label('')->color('primary'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(BecarioExporter::class), // Solo seleccionados
                    DeleteBulkAction::make(),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProyectosRelationManager::class,
            ConvocatoriasRelationManager::class,
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
