<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers\AdscriptosRelationManager;
use App\Filament\Resources\ProyectoResource\RelationManagers\BecariosRelationManager;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Infolists\Components\TextEntry;
use App\Models\Proyecto;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\ProyectoResource\RelationManagers\InvestigadorRelationManager;
use Filament\Forms\Components\Tabs as FormTabs;
use Filament\Forms\Components\Tabs\Tab as FormTab;
use App\Filament\Exports\ProyectoExporter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Proyectos';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Proyectos';
    protected static ?string $slug = 'proyectos-de-investigacion';
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->nombre;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nro', 'nombre', 'actividad.nombre'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Tipo de Actividad' => $record->actividad->nombre,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['actividad']);
    }

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
            FormTabs::make('Proyecto')
                ->tabs([
                    FormTab::make('Datos del Proyecto')
                        ->schema([
                            TextInput::make('nro')
                                ->label('Nro. de P.I.')
                                ->required(),
                            TextInput::make('duracion')
                                ->required(),
                            DatePicker::make('inicio')
                                ->required(),
                            DatePicker::make('fin')
                                ->required(),
                            TextInput::make('nombre')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            RichEditor::make('resumen')
                                ->required()
                                ->maxLength(4000)
                                ->columnSpanFull(),
                        ])->columns(2),

                    FormTab::make('Información Adicional')
                        ->schema([
                            TextInput::make('presupuesto')
                                ->helperText('Si coloca decimales, que sea con un punto "."')
                                ->required(),
                            Toggle::make('estado')
                                ->label('No Vigente / Vigente')
                                ->inline(false)
                                ->required(),
                            TextInput::make('resolucion')
                                ->label('N° Resolución')
                                ->required()
                                ->maxLength(255)
                                // Al cargar el formulario, modifico el valor para mostrar sólo la parte central
                                ->afterStateHydrated(function ($state, callable $set) {
                                    if (!$state) return;

                                    $parteCentral = preg_replace('/^RES-(.*)-C\.S\.$/', '$1', $state);
                                    $set('resolucion', $parteCentral);
                                })
                                // Al guardar, agrego prefijo y sufijo
                                ->dehydrateStateUsing(function ($state) {
                                    return 'RES-' . $state . '-C.S.';
                                }),
                            TextInput::make('disposicion')
                                ->label('N° Disposición')
                                ->required()
                                ->maxLength(255)
                                // Al cargar el formulario, modifico el valor para mostrar sólo la parte central
                                ->afterStateHydrated(function ($state, callable $set) {
                                    if (!$state) return;

                                    $parteCentral = preg_replace('/^RES-(.*)-C\.S\.$/', '$1', $state);
                                    $set('disposicion', $parteCentral);
                                })
                                // Al guardar, agrego prefijo y sufijo
                                ->dehydrateStateUsing(function ($state) {
                                    return 'RES-' . $state . '-C.S.';
                                }),
                            FileUpload::make('pdf_resolucion')
                                ->label('Resolución en .PDF')
                                ->multiple()
                                ->required()
                                ->disk('public')
                                ->directory('resoluciones')
                                ->preserveFilenames()
                                ->reorderable()
                                ->openable(),
                            FileUpload::make('pdf_disposicion')
                                ->label('Disposición en .PDF')
                                ->multiple()
                                ->required()
                                ->disk('public')
                                ->directory('disposiciones')
                                ->preserveFilenames()
                                ->reorderable()
                                ->openable(),
                        ])->columns(2),

                    FormTab::make('Clasificación')
                        ->schema([
                            Select::make('carrera_id')->relationship('carrera', 'nombre')
                                ->required(),
                            Select::make('campo_id')
                                ->relationship('campo', 'nombre')
                                ->required(),
                            Select::make('objetivo_id')
                                ->relationship('objetivo', 'nombre')
                                ->required()
                                ->preload(),
                            Select::make('actividad_id')
                                ->relationship('actividad', 'nombre')
                                ->required(),
                        ])->columns(3),
                ])
                ->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nro')
                    ->label('Nro.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(40),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
                TextColumn::make('inicio')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fin')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('disposicion')
                    ->label('Disposición')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('resolucion')
                    ->label('Resolución')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duracion')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('presupuesto')
                    ->label('Presupuesto')
                    ->formatStateUsing(fn ($state) => '$' . number_format($state, 2, ',', '.'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('campo_id')
                    ->label('Campo de Aplicación')
                    ->relationship('campo', 'nombre'),
                SelectFilter::make('objetivo_id')
                    ->label('Objetivo Socioeconomico')
                    ->relationship('objetivo', 'nombre'),
                SelectFilter::make('actividad_id')
                    ->label('Tipo de Actividad')
                    ->relationship('actividad', 'nombre'),
                Filter::make('rango_completo')
                    ->label('Rango completo del proyecto')
                    ->form([
                        DatePicker::make('desde')->label('Desde'),
                        DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'] && $data['hasta'],
                                fn (Builder $query): Builder => $query
                                    ->where('inicio', '>=', $data['desde'])
                                    ->where('fin', '<=', $data['hasta']),
                            );
                    }),
                ]) /*, layout: FiltersLayout::AboveContent)->filtersFormColumns(2)*/
            ->actions([
                MediaAction::make('ver_resolucion')
                    ->label(fn ($record) => $record->resolucion ?? 'Sin Resolución')
                    ->icon('heroicon-o-document-arrow-down')
                    ->media(fn ($record) => $record->pdf_resolucion
                        ? asset('storage/' . $record->pdf_resolucion[0])
                        : null
                    ),

                ViewAction::make()->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto de Investigación N° ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        InfoTabs::make('Tabs')
                        ->tabs([
                            InfoTab::make('Datos Generales')
                                ->schema([
                                InfoSection::make('')
                                    ->description(fn ($record) => 'Proyecto de Investigación N° ' . $record->nro)
                                    ->schema([
                                        TextEntry::make('investigadorDirector')
                                            ->label('Director del Proyecto')
                                            ->color('customgray')
                                            ->getStateUsing(fn ($record) =>
                                                $record->investigadorDirector->pluck('apellido_nombre')->implode(', ')
                                            ),
                                        TextEntry::make('investigadorCodirector')
                                            ->label('Co-director del Proyecto')
                                            ->color('customgray')
                                            ->getStateUsing(fn ($record) => 
                                                $record->investigadorCodirector->isNotEmpty()
                                                    ? $record->investigadorCodirector->pluck('apellido_nombre')->implode(', ')
                                                    : '-'
                                            ),
                                        TextEntry::make('nombre')
                                            ->label('Denominación del Proyecto')
                                            ->columnSpanFull()
                                            ->color('customgray'),
                                        TextEntry::make('resumen')
                                            ->label('Resumen del Proyecto')
                                            ->columnSpanFull()
                                            ->color('customgray')
                                            ->html(),
                                    ])->columns(2),
                                InfoSection::make('')
                                    ->description('Duración del Proyecto')
                                    ->schema([
                                        TextEntry::make('duracion')->label('Duración en meses')
                                            ->color('customgray'),
                                        TextEntry::make('inicio')->label('Inicio de actividad')
                                            ->color('customgray'),
                                        TextEntry::make('fin')->label('Fin de actividad')
                                            ->color('customgray'),
                                    ])->columns(3),
                                ]),
                            InfoTab::make('Investigadores')
                                ->schema([
                                    Entry::make('investigadores')
                                        ->label('Investigadores Asociados')
                                        ->columnSpanFull()
                                        ->view('livewire.investigadores-list', [
                                            'proyecto' => $action->getRecord(), // PASAR el proyecto aquí
                                        ]),
                                ])->columns(2),
                            InfoTab::make('Becarios')
                                ->schema([
                                    Entry::make('becarios')
                                        ->label('Becarios Asociados')
                                        ->columnSpanFull()
                                        ->view('livewire.becarios-list', [
                                            'proyecto' => $action->getRecord(), // PASAR el proyecto aquí
                                        ]),
                                ])->columns(2),
                            InfoTab::make('Adscriptos')
                                ->schema([
                                    Entry::make('adscriptos')
                                        ->label('Adscriptos Asociados')
                                        ->columnSpanFull()
                                        ->view('livewire.adscriptos-list', [
                                            'proyecto' => $action->getRecord(),
                                        ]),
                                    ]),
                            InfoTab::make('Estado')
                                ->schema([
                                InfoSection::make('')
                                    ->description('Resolución y Estado del Proyecto')
                                    ->schema([
                                        TextEntry::make('estado')
                                            ->label('Estado')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'success' : 'danger')
                                            ->formatStateUsing(fn (bool $state) => $state ? 'Vigente' : 'No Vigente'),
                                        TextEntry::make('presupuesto')->label('Presupuesto')
                                            ->formatStateUsing(fn ($state) => '$' . number_format($state, 2, ',', '.'))
                                            ->color('customgray'),
                                        TextEntry::make('disposicion')->label('Nro. de Disposición')
                                            ->color('customgray'),
                                        TextEntry::make('resolucion')->label('Nro. de Resolución')
                                            ->color('customgray'),
                                        Entry::make('pdf_disposicion')
                                            ->label('Disposiciones en PDF')
                                            ->view('filament.infolists.custom-file-entry-dispo'),
                                        Entry::make('pdf_resolucion')
                                            ->label('Resoluciones en PDF')
                                            ->view('filament.infolists.custom-file-entry-reso'),
                                    ])->columns(2),
                                ]),
                            InfoTab::make('Clasificación')
                                ->schema([
                                InfoSection::make('')
                                    ->description('Datos relevante para el RACT')
                                    ->schema([
                                    TextEntry::make('carrera.nombre')
                                        ->label('Carrera')
                                        ->color('customgray'),
                                        TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                                            ->color('customgray'),
                                        TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                                            ->color('customgray'),
                                        TextEntry::make('actividad.nombre')->label('Tipo Actividad')
                                            ->color('customgray'),
                                    ])->columns(3),
                                ])
                        ])
                ]),
                EditAction::make()->label('Editar'),
            ])
            ->headerActions([
                // ExportAction::make()->exporter(ProyectoExporter::class),
                // ImportAction::make()->importer(ProyectoImporter::class),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()->exporter(ProyectoExporter::class), // Solo seleccionados
                    DeleteBulkAction::make(),
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            InvestigadorRelationManager::class,
            BecariosRelationManager::class,
            AdscriptosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            /*'view' => Pages\ViewProyecto::route('/{record}'),*/
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
