<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\InvestigadorResource\Pages;
use App\Filament\Resources\InvestigadorResource\RelationManagers;
use App\Models\Disciplina;
use App\Models\Investigador;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Forms\Components\Tabs as FormTabs;
use Filament\Forms\Components\Tabs\Tab as FormTab;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestigadorResource extends Resource
{
    protected static ?string $model = Investigador::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Investigadores';
    protected static ?string $modelLabel = 'Investigadores';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $slug = 'investigadores-pi';
    protected static ?int $navigationSort = 2;

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
                FormTabs::make('Formulario del Investigador')
                    ->tabs([
                        FormTab::make('Datos Personales')
                            ->schema([
                                FormSection::make('Datos del Investigador')
                                    ->description('Datos personales.')
                                    ->schema([
                                        TextInput::make('nombre')
                                            ->label('Nombre(s)')
                                            ->required(),
                                        TextInput::make('apellido')
                                            ->label('Apellido(s)')
                                            ->required(),
                                        DatePicker::make('fecha_nac')
                                            ->label('Fecha de nacimiento')
                                            ->required(),
                                        TextInput::make('dni')
                                            ->label('DNI')
                                            ->required(),
                                        TextInput::make('cuil')
                                            ->label('CUIL')
                                            ->required(),
                                        TextInput::make('domicilio')
                                            ->label('Domicilio')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('provincia')
                                            ->label('Provincia')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Correo electrónico')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('telefono')
                                            ->label('Teléfono')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->columns(3),
                            ]),
                        
                        FormTab::make('Clasificación')
                            ->schema([
                                FormSection::make('Clasificación')
                                    ->description('Datos relevantes para el RACT')
                                    ->schema([
                                        Select::make('nivel_academico_id')
                                            ->label('Nivel Académico')
                                            ->relationship('nivelAcademico', 'nombre')
                                            ->required(),

                                        Select::make('objetivo_id')
                                            ->label('Objetivo Socioeconómico')
                                            ->relationship('objetivo', 'nombre')
                                            ->required()
                                            ->preload(),

                                        Select::make('campo_id')
                                            ->label('Campo de Aplicación')
                                            ->relationship('campo', 'nombre')
                                            ->required()
                                            ->live()
                                            ->preload(),

                                        Select::make('disciplina_id')
                                            ->label('Disciplina')
                                            ->options(fn (Get $get): Collection => Disciplina::query()
                                                ->where('campo_id', $get('campo_id'))
                                                ->pluck('nombre', 'id'))
                                            ->required()
                                            ->preload(),

                                        TextInput::make('titulo')
                                            ->label('Título del Investigador')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('titulo_posgrado')
                                            ->label('Título de posgrado')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('cargo_id')
                                            ->label('Cargo docente')
                                            ->relationship('cargo', 'nombre')
                                            ->required()
                                            ->preload(),

                                        Select::make('categoria_interna_id')
                                            ->label('Categoría Interna UNCAUS')
                                            ->relationship('categoriaInterna', 'categoria')
                                            ->required()
                                            ->preload(),

                                        Select::make('incentivo_id')
                                            ->label('Categoría del Incentivo')
                                            ->relationship('incentivo', 'categoria')
                                            ->required()
                                            ->preload(),
                                    ])
                                    ->columns(3),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Nombre'),
                TextColumn::make('apellido')
                    ->label('Apellido'),
                TextColumn::make('dni')
                    ->label('DNI'),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('telefono')
                    ->label('Teléfono'),
                TextColumn::make('disposicion')
                    ->label('Disposición')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->label('Ver')
                ->modalHeading(fn ($record) => 'Detalles del Investigador ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        InfoTabs::make('Tabs')
                        ->tabs([
                            InfoTab::make('Datos Generales')
                                ->schema([
                                InfoSection::make('Detalle del Investigador en los PI.')
                                    ->description()
                                    ->schema([
                                        TextEntry::make('nombre')
                                            ->label('Nombre(s)')
                                            ->color('customgray'),
                                        TextEntry::make('apellido')
                                            ->label('Apellido(s)')
                                            ->color('customgray'),
                                        TextEntry::make('categoriaInterna.categoria')
                                            ->label('Categoría Interna UNCAUS')
                                            ->color('customgray'),
                                        TextEntry::make('incentivo.categoria')
                                            ->label('Categoría de Incentivo')
                                            ->color('customgray'),
                                        TextEntry::make('titulo')
                                            ->label('Título profesional')
                                            ->color('customgray'),
                                        TextEntry::make('titulo_posgrado')
                                            ->label('Título de posgrado')
                                            ->color('customgray'),
                                    ])->columns(2),
                                ]),
                            InfoTab::make('Clasificación')
                                ->schema([
                                InfoSection::make('Clasificación del Investigador según RACT')
                                    ->description()
                                    ->schema([
                                        TextEntry::make('objetivo.nombre')
                                            ->label('Objetivo Socioeconómico')
                                            ->color('customgray'),
                                        TextEntry::make('campo.nombre')
                                            ->label('Campo de Aplicación')
                                            ->color('customgray'),
                                        TextEntry::make('nivelAcademico.nombre')
                                            ->label('Nivel Académico')
                                            ->color('customgray'),
                                    ])->columns(2),
                                ]),
                            InfoTab::make('Contacto')
                                ->schema([
                                InfoSection::make('Datos de Contacto')
                                    ->description()
                                    ->schema([
                                        TextEntry::make('email')
                                            ->label('Correo electrónico')
                                            ->color('customgray'),
                                        TextEntry::make('telefono')
                                            ->label('Teléfono')
                                            ->color('customgray')
                                    ])->columns(2),
                                ]),
                            InfoTab::make('Datos Personales')
                                ->schema([
                                InfoSection::make('Datos personales del Investigador')
                                    ->description()
                                    ->schema([
                                        TextEntry::make('dni')
                                            ->label('DNI')
                                            ->color('customgray'),
                                        TextEntry::make('cuil')
                                            ->label('CUIL')
                                            ->color('customgray'),
                                        TextEntry::make('fecha_nac')
                                            ->label('Fecha de Nacimiento')
                                            ->color('customgray')
                                            ->html(),
                                        TextEntry::make('domicilio')
                                            ->label('Domicilio')
                                            ->color('customgray'),
                                        TextEntry::make('provincia')
                                            ->label('Provincia')
                                            ->color('customgray')
                                    ])->columns(2),
                                ]),
                            ])
                        ])
                ,
                EditAction::make()
                    ->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestigadors::route('/'),
            'create' => Pages\CreateInvestigador::route('/create'),
            'edit' => Pages\EditInvestigador::route('/{record}/edit'),
        ];
    }
}
