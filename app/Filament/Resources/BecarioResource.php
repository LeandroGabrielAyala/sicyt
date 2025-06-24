<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BecarioResource\Pages;
use App\Filament\Resources\BecarioResource\RelationManagers;
use App\Filament\Resources\BecarioResource\RelationManagers\ProyectosRelationManager;
use App\Models\Becario;
use App\Models\TipoBeca;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BecarioResource extends Resource
{
    protected static ?string $model = Becario::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Becarios';
    protected static ?string $navigationGroup = 'Becas';
    protected static ?string $modelLabel = 'Becarios';
    protected static ?string $slug = 'becarios';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';

        //return static::getModel()::count() > 5 ? 'primary' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')
                    ->required()
                    ->label('Nombre(s)'),
                TextInput::make('apellido')
                    ->required()
                    ->label('Apellido(s)'),
                TextInput::make('dni')
                    ->required()
                    ->label('DNI')
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                TextInput::make('cuil')
                    ->required()
                    ->label('CUIL')
                    ->maxLength(15)
                    ->unique(ignoreRecord: true),
                TextInput::make('domicilio')
                    ->required()
                    ->label('Domicilio'),
                TextInput::make('provincia')
                    ->required()
                    ->label('Provincia'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email')
                    ->unique(ignoreRecord: true),
                TextInput::make('telefono')
                    ->required()
                    ->maxLength(20)
                    ->label('Teléfono')
                    ->unique(ignoreRecord: true),
                DatePicker::make('fecha_nac')
                    ->required()
                    ->label('Fecha de nacimiento'),
                // Select::make('tipo_beca_id')
                //     ->label('Tipo de Beca')
                //     ->relationship('tipo_beca', 'nombre')
                //         ->options(TipoBeca::orderBy('nombre', 'desc')->pluck('nombre', 'id')
                //     )
                //     ->required()
                //     ->columnSpanFull()
                //     ->live(),
                Select::make('tipo_beca_id')
                    ->label('Tipo de Beca')
                    ->relationship('tipo_beca', 'nombre')
                    ->required()
                    ->live(),
                RichEditor::make('plan_trabajo')
                        ->required()
                        ->maxLength(4000)
                        ->columnSpanFull(),
                Select::make('carrera_id')
                    ->relationship('carrera', 'nombre')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Grado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Grado')
                    ->columnSpanFull(),

                Select::make('nivel_academico_id')
                    ->relationship('nivelAcademico', 'nombre')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                Select::make('disciplina_id')
                    ->relationship('disciplina', 'nombre')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                Select::make('campo_id')
                    ->relationship('campo', 'nombre')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                Select::make('objetivo_id')
                    ->relationship('objetivo', 'nombre')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                TextInput::make('titulo')
                    ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                    ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')
                    ->label('Apellido(s)')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('nombre')
                    ->label('Nombre(s)')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('dni')
                    ->label('DNI'),
                TextColumn::make('tipo_beca.nombre')
                    ->label('Tipo de Beca')
                    ->searchable()
                    ->limit(50)
                    ->badge()
                    ->color(fn (string $state) => match (true) {
                        str_contains($state, 'Grado') => 'success',
                        str_contains($state, 'Posgrado') => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match (true) {
                        str_contains($state, 'Grado') => 'Grado',
                        str_contains($state, 'Posgrado') => 'Posgrado',
                        default => $state,
                    }),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tipo_beca')
                    ->label('Tipo de Beca')
                    ->relationship('tipo_beca', 'nombre')
                    ->options([
                        'UNCAUS Grado' => 'UNCAUS Grado',
                        'UNCAUS Posgrado' => 'UNCAUS Posgrado',
                        'CIN' => 'CIN',
                    ])
            ])
            ->actions([
                ViewAction::make()->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Becario ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        Tabs::make('Tabs')->tabs([

                            Tab::make('Datos Personales')->schema([
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

                            Tab::make('Contacto')->schema([
                                Section::make('Datos de Contacto')->schema([
                                    TextEntry::make('email')->label('Correo electrónico')->color('customgray'),
                                    TextEntry::make('telefono')->label('Teléfono')->color('customgray'),
                                ])->columns(2),
                            ]),

                            Tab::make('Datos de la Beca')->schema([
                                Section::make('Información general de la beca')->schema([
                                    TextEntry::make('tipo_beca.nombre')
                                        ->label('Tipo de Beca')
                                        ->badge()
                                        ->color(fn (string $state) => match (true) {
                                            str_contains($state, 'Grado') => 'success',
                                            str_contains($state, 'Posgrado') => 'info',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn (string $state) => match (true) {
                                            str_contains($state, 'Grado') => 'Grado',
                                            str_contains($state, 'Posgrado') => 'Posgrado',
                                            default => $state,
                                        }),
                                    TextEntry::make('plan_trabajo')
                                        ->label('Plan de trabajo')
                                        ->html()
                                        ->color('customgray')
                                        ->columnSpanFull(),
                                ])->columns(2),
                            ]),

                            Tab::make('Datos Académicos')->schema([
                                Section::make('Formación del Becario')->schema([
                                    TextEntry::make('titulo')->label('Título profesional')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Posgrado')->color('customgray'),

                                    TextEntry::make('carrera.nombre')->label('Carrera')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Grado')->color('customgray'),

                                    TextEntry::make('nivelAcademico.nombre')->label('Nivel Académico')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Posgrado')->color('customgray'),

                                    TextEntry::make('disciplina.nombre')->label('Disciplina')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Posgrado')->color('customgray'),

                                    TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Posgrado')->color('customgray'),

                                    TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                                        ->visible(fn ($record) => $record->tipo_beca?->nombre === 'UNCAUS Posgrado')->color('customgray'),
                                ])->columns(2),
                            ]),

                            Tab::make('Proyectos')->schema([
                                Section::make('Proyectos Asociados')->schema([
                                    \Filament\Infolists\Components\Entry::make('proyectos')
                                        ->label('Proyectos Asociados')
                                        ->view('livewire.proyectos-becarios-list', [
                                            'becario' => $action->getRecord(),
                                        ]),
                                ]),
                            ]),
                        ])
                        ]),

                EditAction::make(),
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
            ProyectosRelationManager::class,
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
