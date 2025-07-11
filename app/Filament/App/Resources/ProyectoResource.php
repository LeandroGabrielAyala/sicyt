<?php

namespace App\Filament\App\Resources;

use Illuminate\Support\Facades\Storage;
use App\Filament\App\Resources\ProyectoResource\Pages;
use App\Filament\App\Resources\ProyectoResource\RelationManagers;
use App\Models\Proyecto;
use Filament\Facades\Filament;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\IconEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section as FormSection;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoResource extends Resource
{
    protected static ?string $model = Proyecto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        /*->storeFileNamesIn('pdf_resolucion'),
                        ->getUploadedFileNameForStorageUsing(function ($file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeName = Str::slug($originalName); // elimina espacios, acentos, etc.
                            $extension = $file->getClientOriginalExtension();
                            return $safeName . '-' . Str::random(6) . '.' . $extension;
                        }),*/
                    FileUpload::make('pdf_disposicion')
                        ->label('Disposición en .PDF')
                        ->multiple()
                        ->required()
                        ->disk('public')
                        ->directory('disposiciones')
                        ->preserveFilenames()
                        ->reorderable()
                        ->openable()
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
                            // modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))
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
                //
            ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProyectos::route('/'),
            'create' => Pages\CreateProyecto::route('/create'),
            // 'view' => Pages\ViewProyecto::route('/{record}'),
            'edit' => Pages\EditProyecto::route('/{record}/edit'),
        ];
    }
}
