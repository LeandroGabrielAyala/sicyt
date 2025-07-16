<?php

namespace App\Filament\Resources\InvestigadorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ProyectoRelationManager extends RelationManager
{
    protected static string $relationship = 'proyectos'; // debe coincidir con el nombre de la relación en Investigador.php

    protected static ?string $title = 'Proyectos Asociados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nro')->label('Nro. PI'),
                TextColumn::make('nombre')->label('Proyecto')->limit(50),
                TextColumn::make('investigadorDirector')
                    ->label('Director')
                    ->color('customgray')
                    ->getStateUsing(fn ($record) =>
                        $record->investigadorDirector->pluck('apellido_nombre')->implode(', ')
                    ),
                TextColumn::make('investigadorCodirector')
                    ->label('Co-director')
                    ->color('customgray')
                    ->getStateUsing(fn ($record) =>
                        $record->investigadorCodirector->isNotEmpty()
                            ? $record->investigadorCodirector->pluck('apellido_nombre')->implode(', ')
                            : '-'
                    ),
                TextColumn::make('pivot.funcion.nombre')->label('Función'),
                TextColumn::make('pivot.inicio')->label('Inicio')->date(),
                TextColumn::make('pivot.fin')->label('Fin')->date(),
                IconColumn::make('pivot.vigente')->label('Vigente')->boolean(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto N° ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn ($record) => [
                        Tabs::make('Tabs')
                            ->tabs([
                                Tab::make('Datos Generales')->schema([
                                    Section::make('')
                                        ->description('Proyecto de Investigación N° ' . $record->nro)
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
                                    Section::make('')
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

                                Tab::make('Estado')->schema([
                                    Section::make('')
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

                                Tab::make('Clasificación')->schema([
                                    Section::make('')
                                        ->description('Datos relevantes para el RACT')
                                        ->schema([
                                            TextEntry::make('carrera.nombre')->label('Carrera')->color('customgray'),
                                            TextEntry::make('campo.nombre')->label('Campo de Aplicación')->color('customgray'),
                                            TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')->color('customgray'),
                                            TextEntry::make('actividad.nombre')->label('Tipo de Actividad')->color('customgray'),
                                        ])->columns(3),
                                ]),
                            ])
                    ])
            ]);
    }


    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // No se usa para crear desde este lado
    }
}
