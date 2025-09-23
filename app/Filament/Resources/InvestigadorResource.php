<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\InvestigadorResource\RelationManagers\ProyectoRelationManager;
use App\Filament\Resources\InvestigadorResource\RelationManagers\BecariosRelationManager;
use App\Filament\Resources\InvestigadorResource\RelationManagers\AdscriptosRelationManager;
use App\Filament\Resources\InvestigadorResource\Pages;
use App\Models\Disciplina;
use App\Models\Investigador;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Tabs as FormTabs;
use Filament\Forms\Components\Tabs\Tab as FormTab;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class InvestigadorResource extends Resource
{
    protected static ?string $model = Investigador::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Investigadores';
    protected static ?string $modelLabel = 'Investigadores';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $slug = 'investigadores-pi';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

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
            'Título' => $record->titulo,
            'Cargo' => optional($record->cargo)->nombre ?? '—',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['cargo']);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            FormTabs::make('Formulario del Investigador')
                ->tabs([
                    FormTab::make('Datos Personales')
                        ->schema([
                            FormSection::make('Datos del Investigador')
                                ->description('Datos personales.')
                                ->schema([
                                    TextInput::make('nombre')->label('Nombre(s)')->required(),
                                    TextInput::make('apellido')->label('Apellido(s)')->required(),
                                    DatePicker::make('fecha_nac')->label('Fecha de nacimiento')->required(),
                                    TextInput::make('lugar_nac')->label('Lugar de Nacimiento')->required(),
                                    TextInput::make('dni')->label('DNI')->required(),
                                    TextInput::make('cuil')->label('CUIL')->required(),
                                    TextInput::make('domicilio')->label('Domicilio')->required()->maxLength(255),
                                    TextInput::make('provincia')->label('Provincia')->required()->maxLength(255),
                                    TextInput::make('email')
                                        ->label('Correo electrónico')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(User::class, 'email'), // Validación: email único en users
                                    TextInput::make('telefono')->label('Teléfono')->required()->maxLength(255),
                                ])->columns(3),
                        ]),
                    FormTab::make('Clasificación')
                        ->schema([
                            FormSection::make('Clasificación')
                                ->description('Datos relevantes para el RACT')
                                ->schema([
                                    Select::make('nivel_academico_id')->label('Nivel Académico')->relationship('nivelAcademico', 'nombre')->required(),
                                    Select::make('objetivo_id')->label('Objetivo Socioeconómico')->relationship('objetivo', 'nombre')->required()->preload(),
                                    Select::make('campo_id')->label('Campo de Aplicación')->relationship('campo', 'nombre')->required()->live()->preload(),
                                    Select::make('disciplina_id')->label('Disciplina')->options(fn (Get $get): Collection => Disciplina::query()->where('campo_id', $get('campo_id'))->pluck('nombre', 'id'))->required()->preload(),
                                    Select::make('carrera_id')->relationship('carrera', 'titulo')->label('Título')->required(),
                                    TextInput::make('titulo_posgrado')->label('Título de posgrado')->required()->maxLength(255),
                                    Select::make('cargo_id')->label('Cargo docente')->relationship('cargo', 'nombre')->required()->preload(),
                                    Select::make('categoria_interna_id')->label('Categoría Interna UNCAUS')->relationship('categoriaInterna', 'categoria')->required()->preload(),
                                    Select::make('incentivo_id')->label('Categoría del Incentivo')->relationship('incentivo', 'categoria')->required()->preload(),
                                ])->columns(3),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')->label('Apellido')->searchable(),
                TextColumn::make('nombre')->label('Nombre')->searchable(),
                TextColumn::make('dni')->label('DNI')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('telefono')->label('Teléfono')->searchable(),
                TextColumn::make('disposicion')->label('Disposición')->searchable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('apellido', 'asc')
            ->filters([
                SelectFilter::make('campo_id')->label('Campo de Aplicación')->relationship('campo', 'nombre'),
                SelectFilter::make('objetivo_id')->label('Objetivo Socioeconomico')->relationship('objetivo', 'nombre'),
                SelectFilter::make('disciplina_id')->label('Disciplina')->relationship('disciplina', 'nombre'),
                SelectFilter::make('carrera_id')->label('Título del Investigador')->relationship('carrera', 'titulo'),
                SelectFilter::make('cargo_id')->label('Cargo docente')->relationship('cargo', 'nombre'),
                SelectFilter::make('categoria_interna_id')->label('Categoría Interna UNCAUS')->relationship('categoriaInterna', 'categoria'),
                SelectFilter::make('incentivo_id')->label('Categoría del Incentivo')->relationship('incentivo', 'categoria'),
            ])
            ->actions([
                ViewAction::make()->label('')->color('primary'),
                EditAction::make()->label(''),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(\App\Filament\Exports\InvestigadorExporter::class)->label('Exportar investigadores'),
                    DeleteBulkAction::make()->label('Eliminar seleccionados')->requiresConfirmation()->modalHeading('¿Estás seguro de querer eliminar los registros?')->modalSubheading('Se eliminará completamente y no podrás recuperarlo.')->modalButton('Sí, eliminar')->modalIcon('heroicon-o-trash')
                        ->successNotification(Notification::make()->success()->title('Investigador Eliminado')->body('El investigador fue eliminado correctamente')),
                ])->label('Acciones')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProyectoRelationManager::class,
            BecariosRelationManager::class,
            AdscriptosRelationManager::class,
        ];
    }

public static function afterCreate(Investigador $record, array $data): void
{
    // Crear usuario automáticamente
    $user = User::create([
        'name' => $data['nombre'] . ' ' . $data['apellido'],
        'email' => $data['email'],
        'password' => bcrypt($data['dni']),
    ]);

    // Actualizar el investigador con el user_id
    $record->update([
        'user_id' => $user->id,
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
