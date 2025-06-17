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
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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

    public static function form(Form $form): Form
    {
        return $form
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
                        ->maxLength(255)
                ])->columns(3),
                FormSection::make('Proyecto de Investigación')
                ->description('Datos del proyecto donde se inserta.')
                ->schema([
                    Select::make('proyecto_id')
                        ->label('Proyecto de Investigación')
                        ->relationship('proyecto', 'nro')
                        ->required(),
                    Toggle::make('estado')
                        ->label('No Activo / Activo')
                        ->inline(false)
                        ->required(),
                    DatePicker::make('inicio')
                        ->label('Inicio de actividad')
                        ->required(),
                    DatePicker::make('fin')
                        ->label('Fin de actividad')
                        ->required(),
                    TextInput::make('disposicion')
                        ->label('Disposición')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('resolucion')
                        ->label('Resolución')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('pdf_disposicion')
                        ->label('Disposición en .PDF')
                        ->multiple()
                        ->required()
                        ->disk('public')
                        ->directory('disposiciones_inv')
                        ->preserveFilenames()
                        ->reorderable()
                        ->openable(),
                    FileUpload::make('pdf_resolucion')
                        ->label('Resolución en .PDF')
                        ->multiple()
                        ->required()
                        ->disk('public')
                        ->directory('resoluciones_inv')
                        ->preserveFilenames()
                        ->reorderable()
                        ->openable(),
                ])->columns(2),
                FormSection::make('Clasificación')
                ->description('Datos relevante para el RACT')
                ->schema([
                    Select::make('funcion_id')
                        ->label('Función en PI')
                        ->relationship('funcion', 'nombre')
                        ->required(),
                    Select::make('nivel_academico_id')
                        ->label('Nivel Académico')
                        ->relationship('nivelAcademico', 'nombre')
                        ->required(),
                    Select::make('objetivo_id')
                        ->label('Objetivo Socioeconomico')
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
                ])->columns(3),
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
                TextColumn::make('proyecto.nro')
                    ->label('Nro. PI'),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
                TextColumn::make('inicio')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fin')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('disposicion')
                    ->label('Disposición')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('resolucion')
                    ->label('Resolución')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                /*TextColumn::make('pdf_resolucion')
                    ->label('Descargar .PDF')
                    ->searchable()
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'primary' : 'primary')
                    ->formatStateUsing(fn ($record) => 'Descargar ' . $record->resolucion)
                    ->url(fn ($record) => Storage::url($record->pdf_resolucion))
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),*/
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
                        Tabs::make('Tabs')
                        ->tabs([
                            Tab::make('Datos Generales')
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
                                        TextEntry::make('proyecto.nro')
                                            ->label('Proyecto de Investigación')
                                            ->color('customgray'),
                                        TextEntry::make('estado')
                                            ->label('Estado')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'success' : 'danger')
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Activo' : 'No Activo'),
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
                                        TextEntry::make('disposicion')->label('Nro. de Disposición')
                                            ->color('customgray'),
                                        TextEntry::make('resolucion')->label('Nro. de Resolución')
                                            ->color('customgray'),
                                        Entry::make('pdf_disposicion')
                                            ->label('Disposiciones en PDF')
                                            ->view('filament.infolists.custom-file-entry-dispo-inv'),
                                        Entry::make('pdf_resolucion')
                                            ->label('Resoluciones en PDF')
                                            ->view('filament.infolists.custom-file-entry-reso-inv'),
                                    ])->columns(2),
                                ]),
                            Tab::make('Clasificación')
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
                            Tab::make('Contacto')
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
                            Tab::make('Datos Personales')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
