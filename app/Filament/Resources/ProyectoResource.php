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
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;

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
                    TextInput::make('resolucion')
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('pdf_resolucion')
                        ->label('Resolución en .PDF')
                        ->required()
                        ->disk('public')
                        ->directory('resoluciones')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(1024)
                        ->getUploadedFileNameForStorageUsing(function ($file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $safeName = Str::slug($originalName); // elimina espacios, acentos, etc.
                            $extension = $file->getClientOriginalExtension();
                            return $safeName . '-' . Str::random(6) . '.' . $extension;
                        }),
                    TextInput::make('presupuesto')
                        ->required(),
                    Toggle::make('estado')
                        ->label('No Vigente / Vigente')
                        ->inline(false)
                        ->required(),
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
                        ->searchable()
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
                    ->limit(70),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
                TextColumn::make('resolucion')
                    ->label('# Resolución')
                    ->searchable(),
                /*TextColumn::make('resumen')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('campo_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('objetivo_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('actividad_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),*/
                TextColumn::make('duracion')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('inicio')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fin')
                    ->date()
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
                    ->numeric()
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
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto N° ' . $record->nro)
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
                                            ->weight(FontWeight::Thin)
                                            ->extraAttributes([
                                                'style' => 'color: #2A9D8F !important;', // verde azulado personalizado
                                            ]),
                                        TextEntry::make('resumen')
                                            ->label('Resumen del Proyecto')
                                            ->columnSpanFull()
                                            ->html(),
                                    ]),
                                InfoSection::make('')
                                    ->description('Duración del Proyecto')
                                    ->schema([
                                        TextEntry::make('duracion')->label('Duración en meses'),
                                        TextEntry::make('inicio')->label('Inicio de actividad'),
                                        TextEntry::make('fin')->label('Fin de actividad'),
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
                                        TextEntry::make('presupuesto')->label('Presupuesto'),
                                        TextEntry::make('resolucion')->label('Nro. de Resolución'),
                                        TextEntry::make('pdf_resolucion')
                                            ->label('Descargar la Resolución en .PDF')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'primary' : 'primary')
                                            ->formatStateUsing(fn ($record) => 'Resolución N° ' . $record->resolucion)
                                            ->url(fn ($record) => Storage::url($record->pdf_resolucion))
                                            ->openUrlInNewTab(),
                                    ])->columns(2),
                                ]),
                            Tab::make('Clasificación')
                                ->schema([
                                InfoSection::make('')
                                    ->description('Datos relevante para el RACT')
                                    ->schema([
                                        TextEntry::make('campo.nombre')->label('Campo de Aplicación'),
                                        TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico'),
                                        TextEntry::make('actividad.nombre')->label('Tipo Actividad'),
                                    ])->columns(2),
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
