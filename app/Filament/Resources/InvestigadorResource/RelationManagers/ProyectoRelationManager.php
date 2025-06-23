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
                TextColumn::make('pivot.funcion.nombre')->label('Función'),
                TextColumn::make('pivot.inicio')->label('Inicio')->date(),
                TextColumn::make('pivot.fin')->label('Fin')->date(),
                IconColumn::make('pivot.vigente')->label('Vigente')->boolean(),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto Nº ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn ($record) => [
                        Tabs::make('Tabs')
                            ->tabs([
                                Tab::make('Participación')->schema([
                                    Section::make('Detalles de Participación')
                                        ->schema([
                                            TextEntry::make('nombre')->label('Nombre')->color('customgray')->columnSpanFull(),
                                            TextEntry::make('pivot.funcion.nombre')->label('Función'),
                                            TextEntry::make('pivot.vigente')
                                                ->label('Estado en el P.I.')
                                                ->badge()
                                                ->color(fn (bool $state) => $state ? 'success' : 'danger')
                                                ->formatStateUsing(fn (bool $state) => $state ? 'Vigente' : 'No Vigente'),
                                            TextEntry::make('vigente')
                                                ->label('Vigencia del P.I.')
                                                ->badge()
                                                ->color(fn (bool $state) => $state ? 'success' : 'danger')
                                                ->formatStateUsing(fn (bool $state) => $state ? 'Vigente' : 'No Vigente'),
                                            TextEntry::make('duracion')->label('Duración en meses'),
                                            TextEntry::make('inicio')->label('Inicio de actividad'),
                                            TextEntry::make('fin')->label('Fin de actividad'),
                                            Entry::make('pdf_disposicion')
                                                ->label('Disposición del P.I.')
                                                ->view('filament.infolists.custom-file-entry-dispo'),
                                            Entry::make('pdf_resolucion')
                                                ->label('Resolución del P.I.')
                                                ->view('filament.infolists.custom-file-entry-reso'),
                                        ])->columns(3),
                                ]),
                                Tab::make('Resumen')->schema([
                                    Section::make('')
                                        ->schema([
                                            TextEntry::make('resumen')->label('Resumen del Proyecto')->color('customgray')->html()->columnSpanFull(),
                                        ])->columns(1),
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
