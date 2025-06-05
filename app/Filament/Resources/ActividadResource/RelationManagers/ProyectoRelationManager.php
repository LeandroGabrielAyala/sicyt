<?php

namespace App\Filament\Resources\ActividadResource\RelationManagers;

use Illuminate\Support\Facades\Storage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProyectoRelationManager extends RelationManager
{
    protected static string $relationship = 'proyecto';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
                                        TextEntry::make('pdf_disposicion')
                                            ->label('Descargar la Disposición en .PDF')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'info' : 'info')
                                            ->formatStateUsing(fn ($record) => 'Disposición N° ' . $record->disposicion)
                                            ->url(fn ($record) => Storage::url($record->pdf_disposicion))
                                            ->openUrlInNewTab(),
                                        TextEntry::make('resolucion')->label('Nro. de Resolución')
                                            ->color('customgray'),
                                        TextEntry::make('pdf_resolucion')
                                            ->label('Descargar la Resolución en .PDF')
                                            ->badge()
                                            ->color(fn (bool $state) => $state ? 'info' : 'info')
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
}
