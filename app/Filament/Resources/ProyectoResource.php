<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Filament\Resources\ProyectoResource\Pages;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\IconEntry;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\ProyectoResource\RelationManagers\InvestigadorRelationManager;
use Livewire\Livewire;

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
                FormSection::make('Datos del Proyecto')
                ->description('Datos generales del Proyecto')
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
                    // Select::make('investigadores')
                    //     ->label('Integrantes')
                    //     ->multiple()
                    //     ->columnSpanFull()
                    //     ->relationship('investigadores', 'apellido') // La columna es irrelevante, se sobreescribe
                    //     ->preload()
                    //     ->searchable()
                    //     ->getOptionLabelFromRecordUsing(fn ($record) => $record->apellido . ', ' . $record->nombre)
                    //     ->getSearchResultsUsing(function (string $search) {
                    //         return \App\Models\Investigador::query()
                    //             ->whereRaw("CONCAT(apellido, ' ', nombre) LIKE ?", ["%{$search}%"])
                    //             ->limit(50)
                    //             ->get()
                    //             ->mapWithKeys(fn ($inv) => [$inv->id => $inv->apellido . ', ' . $inv->nombre]); // 👈 ESTA PARTE CAMBIÓ
                    //     })
                    //     ->getOptionLabelsUsing(function ($values) {
                    //         return \App\Models\Investigador::whereIn('id', $values)
                    //             ->get()
                    //             ->mapWithKeys(fn ($inv) => [$inv->id => $inv->apellido . ', ' . $inv->nombre]);
                    //     }),
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    RichEditor::make('resumen')
                        ->required()
                        ->maxLength(4000)
                        ->columnSpanFull(),
                ])->columns(4),
                FormSection::make('Información Adicional')
                ->description('Resolución y Estado del Proyecto')
                ->schema([
                    TextInput::make('presupuesto')
                        ->helperText('Si coloca decimales, que sea con un punto "."')
                        ->required(),
                    Toggle::make('estado')
                        ->label('No Vigente / Vigente')
                        ->inline(false)
                        ->required(),
                    TextInput::make('resolucion')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('disposicion')
                        ->required()
                        ->maxLength(255),
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
                        ->openable()
                    ])->columns(2),
                FormSection::make('Clasificación')
                ->description('Datos relevante para el RACT')
                ->schema([
                    Select::make('campo_id')
                        ->relationship('campo', 'nombre')
                        ->required(),
                    Select::make('objetivo_id')
                        ->relationship('objetivo', 'nombre')
                        ->required()
                        //->searchable()
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
                TextColumn::make('nro')
                    ->label('Nro.')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(50),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
                TextColumn::make('inicio')
                    ->date()
                    ->sortable(),
                TextColumn::make('fin')
                    ->date()
                    ->sortable(),
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
                /*TextColumn::make('pdf_resolucion')
                    ->label('Descargar .PDF')
                    ->searchable()
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'primary' : 'primary')
                    ->formatStateUsing(fn ($record) => 'Descargar ' . $record->resolucion)
                    ->url(fn ($record) => Storage::url($record->pdf_resolucion))
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),*/
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
                ViewAction::make()->label('Ver')
                    //->modalHeading('Detalles del Proyecto')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto de Investigación N° ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        Tabs::make('Tabs')
                        ->tabs([
                            Tab::make('Investigadores')
                                ->schema([
                                    Entry::make('investigadores')
                                        ->label('Investigadores Asociados')
                                        ->view('livewire.proyecto-investigadores-list', [
                                            'proyecto' => $action->getRecord(), // PASAR el proyecto aquí
                                        ]),
                                ])->columns(2),
                            Tab::make('Datos Generales')
                                ->schema([
                                InfoSection::make('')
                                    ->description(fn ($record) => 'Proyecto de Investigación N° ' . $record->nro)
                                    ->schema([
                                        TextEntry::make('nombre')
                                            ->label('Denominación del Proyecto')
                                            ->columnSpanFull()
                                            ->color('customgray'),
                                        TextEntry::make('resumen')
                                            ->label('Resumen del Proyecto')
                                            ->columnSpanFull()
                                            ->color('customgray')
                                            ->html(),
                                        RepeatableEntry::make('investigadores')
                                            ->label('Integrantes')
                                            ->schema([
                                                TextEntry::make('apellido')->label('Apellido(s)'),
                                                TextEntry::make('nombre')->label('Nombre(s)'),
                                                TextEntry::make('dni')->label('DNI'),
                                                // TextEntry::make('estado')->label('Estado')->badge()->color(fn (bool $state) => $state ? 'success' : 'danger')->formatStateUsing(fn (bool $state) => $state ? 'Activo' : 'No Activo'),
                                                // TextEntry::make('inicio')->label('Inicio de Actividad'),
                                                // TextEntry::make('fin')->label('Fin de Actividad'),
                                                // TextEntry::make('disposicion')->label('N° de Dispocisión'),
                                                // TextEntry::make('resolucion')->label('N° de Resolución'),
                                                // Entry::make('pdf_disposicion')
                                                // ->label('Disposiciones en PDF')
                                                // ->view('filament.infolists.custom-file-entry-dispo'),
                                                // Entry::make('pdf_resolucion')
                                                // ->label('Resoluciones en PDF')
                                                // ->view('filament.infolists.custom-file-entry-reso'),
                                            ])->columns(3),
                                    ]),
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
                            Tab::make('Estado')
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
                            Tab::make('Clasificación')
                                ->schema([
                                InfoSection::make('')
                                    ->description('Datos relevante para el RACT')
                                    ->schema([
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
                Tables\Actions\EditAction::make()->label('Editar')
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
            InvestigadorRelationManager::class,
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
