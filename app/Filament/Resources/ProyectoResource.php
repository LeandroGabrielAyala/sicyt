<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Filament\Resources\ProyectoResource\Pages;
use App\Filament\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

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
                    TextInput::make('nombre')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    RichEditor::make('resumen')
                        ->required()
                        ->maxLength(1000)
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
                        ->reorderable(),
                        /*->storeFileNamesIn('pdf_resolucion'),
                        ->getUploadedFileNameForStorageUsing(function ($file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeName = Str::slug($originalName); // elimina espacios, acentos, etc.
                            $extension = $file->getClientOriginalExtension();
                            return $safeName . '-' . Str::random(6) . '.' . $extension;
                        }),*/
                    FileUpload::make('pdf_disposicion')
                        ->label('Disposición en .PDF')
                        ->required()
                        ->disk('public')
                        ->directory('disposiciones')
                        ->preserveFilenames()
                        ->reorderable()
                        /*->storeFileNamesIn('pdf_resolucion'),
                        ->getUploadedFileNameForStorageUsing(function ($file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeName = Str::slug($originalName); // elimina espacios, acentos, etc.
                            $extension = $file->getClientOriginalExtension();
                            return $safeName . '-' . Str::random(6) . '.' . $extension;
                        }),*/
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
                TextColumn::make('pdf_resolucion')
                    ->label('Descargar .PDF')
                    ->searchable()
                    ->badge()
                    ->color(fn (bool $state) => $state ? 'primary' : 'primary')
                    ->formatStateUsing(fn ($record) => 'Descargar ' . $record->resolucion)
                    ->url(fn ($record) => Storage::url($record->pdf_resolucion))
                    ->openUrlInNewTab()
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
                ViewAction::make()->label('Ver')
                    //->modalHeading('Detalles del Proyecto')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto de Investigación N° ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        Tabs::make('Tabs')
                        ->tabs([
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
                                        /*TextEntry::make('pdf_disposicion')
                                            ->label('Descargar la Disposición en .PDF')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'info' : 'info')
                                            ->formatStateUsing(fn ($record) => 'Disposición N° ' . $record->disposicion)
                                            ->url(fn ($record) => Storage::url($record->pdf_disposicion))
                                            ->openUrlInNewTab(),*/
                                        TextEntry::make('pdf_disposicion')
                                            ->label('Disposiciones en PDF')
                                            ->formatStateUsing(function ($state, $record) {
                                                // $state es el array de nombres guardados en la BD
                                                if (empty($state) || !is_array($state)) {
                                                    return 'Sin archivo';
                                                }

                                                // Armamos un listado con links a cada archivo
                                                $links = [];
                                                foreach ($state as $fileName) {
                                                    $url = Storage::url('disposiciones/' . $fileName);
                                                    $links[] = '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $fileName . '</a>';
                                                }

                                                // Devolvemos los links separados por salto de línea
                                                return implode('<br>', $links);
                                            })
                                            ->html(), // IMPORTANTE: permitimos HTML para los links
                                        TextEntry::make('resolucion')->label('Nro. de Resolución')
                                            ->color('customgray'),
                                        TextEntry::make('pdf_resolucion')
                                            ->label('Descargar la Resolución en .PDF')
                                            ->formatStateUsing(function ($state, $record) {
                                                if (empty($state)) {
                                                    return 'Sin archivos';
                                                }

                                                $files = is_array($state) ? $state : json_decode($state, true);

                                                if (!$files || count($files) === 0) {
                                                    return 'Sin archivos';
                                                }

                                                $links = collect($files)->map(function ($file, $index) use ($record) {
                                                    $url = Storage::url($file);
                                                    return "<a href='{$url}' target='_blank' style='color:#2563eb; text-decoration:underline;'>📎 Disposición N° {$record->resolucion} - Archivo " . ($index + 1) . "</a>";
                                                })->implode('<br>');

                                                return $links;
                                            })
                                            ->html(),
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
                    /*Tables\Actions\ViewAction::make(),*/
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
            //
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
