<?php

namespace App\Filament\Resources\BecarioResource\RelationManagers;

use App\Models\ConvocatoriaBeca;
use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class ProyectosRelationManager extends RelationManager
{
    protected static string $relationship = 'proyectos'; // método en Becario.php

    protected static ?string $title = 'Proyectos Asociados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nro')
                    ->label('Nro. de Proyecto'),
                TextColumn::make('investigadorDirector')
                    ->label('Director del Proyecto')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->investigadorDirector->first()?->apellido_nombre ?? '—'
                    ),
                TextColumn::make('investigadorCodirector')
                    ->label('Codirector del Proyecto')
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->investigadorCodirector->first()?->apellido_nombre ?? '—'
                    ),
                TextColumn::make('pivot.tipo_beca')
                    ->label('Tipo de Beca')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Grado' => 'success',
                        'Posgrado' => 'info',
                        'CIN' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => $state ?? '—'),
                TextColumn::make('pivot.convocatoria_beca_id')
                    ->label('Convocatoria')
                    ->formatStateUsing(fn ($state) => 
                        optional(\App\Models\ConvocatoriaBeca::with('tipoBeca')->find($state))->tipoBeca?->nombre
                        . ' (' . optional(\App\Models\ConvocatoriaBeca::find($state))->anio . ')'
                        ?? '—'
                    ),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Proyecto de Investigación N° ' . $record->nro)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        Tabs::make('Tabs')->tabs([
                            Tab::make('Investigadores')->schema([
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
                                            : '—'
                                    ),
                                Entry::make('investigadores')
                                    ->label('Investigadores Asociados')
                                    ->columnSpanFull()
                                    ->view('livewire.investigadores-list', [
                                        'proyecto' => $action->getRecord(),
                                    ]),
                            ])->columns(2),

                            Tab::make('Becarios')->schema([
                                Entry::make('becarios')
                                    ->label('Becarios Asociados')
                                    ->columnSpanFull()
                                    ->view('livewire.becarios-list', [
                                        'proyecto' => $action->getRecord(),
                                    ]),
                            ]),

                            Tab::make('Datos Generales')->schema([
                                Section::make('')
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
                                        TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                                            ->color('customgray'),
                                        TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                                            ->color('customgray'),
                                        TextEntry::make('actividad.nombre')->label('Tipo de Actividad')
                                            ->color('customgray'),
                                    ])->columns(3),
                            ]),
                        ])
                    ]),

            ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // no es necesario un formulario aquí
    }
}
