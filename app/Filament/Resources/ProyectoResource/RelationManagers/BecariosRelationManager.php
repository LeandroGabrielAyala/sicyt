<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Becario;
use App\Models\Investigador;
use App\Models\ConvocatoriaBeca;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Tabs\Tab;

class BecariosRelationManager extends RelationManager
{
    protected static string $relationship = 'Becarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')
                    ->label('Nombre completo')
                    ->formatStateUsing(fn ($state, $record) => $record->apellido . ', ' . $record->nombre),
                TextColumn::make('pivot.director_id')
                    ->label('Director')
                    ->formatStateUsing(fn ($state) => \App\Models\Investigador::find($state)?->apellido . ', ' . \App\Models\Investigador::find($state)?->nombre),
                TextColumn::make('pivot.codirector_id')
                    ->label('Codirector')
                    ->formatStateUsing(fn ($state) => $state ? \App\Models\Investigador::find($state)?->apellido . ', ' . \App\Models\Investigador::find($state)?->nombre : '-'),
                TextColumn::make('pivot.tipo_beca')
                    ->label('Tipo de Beca')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Grado' => 'success',
                        'Posgrado' => 'info',
                        'CIN' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('convocatoria')
                    ->label('Convocatoria (Año)')
                    ->getStateUsing(function ($record) {
                        $tipo = $record->pivot?->convocatoria?->tipoBeca?->nombre;
                        $anio = $record->pivot?->convocatoria?->anio;

                        return $tipo && $anio ? "$tipo ($anio)" : ($tipo ?? $anio ?? '—');
                    })
                    ->badge()
                    ->color('gray'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->form([
                        Grid::make(2)->schema([
                            Select::make('recordId')
                                ->label('Becario')
                                ->options(Becario::all()->pluck('nombre_completo', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('director_id')
                                ->label('Director de la Beca')
                                ->options(function (callable $get) {
                                    $codirectorId = $get('codirector_id');
                                    return Investigador::when($codirectorId, function ($query) use ($codirectorId) {
                                        $query->where('id', '!=', $codirectorId);
                                    })->get()->pluck('nombre_completo', 'id');  // <- primero get(), luego pluck()
                                })
                                ->searchable()
                                ->reactive()
                                ->required(),
                            Select::make('codirector_id')
                                ->label('Codirector de la Beca')
                                ->options(function (callable $get) {
                                    $directorId = $get('director_id');
                                    return Investigador::when($directorId, function ($query) use ($directorId) {
                                        $query->where('id', '!=', $directorId);
                                    })->get()->pluck('nombre_completo', 'id');  // <- igual acá
                                })
                                ->searchable()
                                ->reactive(),
                            Select::make('tipo_beca_convocatoria')
                                ->label('Tipo de Convocatoria')
                                ->options(\App\Models\TipoBeca::all()->pluck('nombre', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('convocatoria_beca_id')
                                ->label('Convocatoria')
                                ->options(ConvocatoriaBeca::all()->pluck('anio', 'id'))
                                ->required(),
                            Select::make('tipo_beca')
                                ->label('Tipo de Beca')
                                ->options(\App\Models\BecarioProyecto::tiposBeca())
                                ->required(),
                            Toggle::make('vigente')
                                ->label('Vigente / No Vigente')
                                ->default(true),
                            TextInput::make('plan_trabajo')
                                ->label('Plan de trabajo')
                                ->required()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ])
            ->actions([
                ViewAction::make()->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Becario ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn () => null)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                        Tabs::make('Tabs')->tabs([

                            Tab::make('Datos Personales')->schema([
                                Section::make('Datos personales del Becario')->schema([
                                    TextEntry::make('nombre')->label('Nombre(s)')->color('customgray'),
                                    TextEntry::make('apellido')->label('Apellido(s)')->color('customgray'),
                                    TextEntry::make('dni')->label('DNI')->color('customgray'),
                                    TextEntry::make('cuil')->label('CUIL')->color('customgray'),
                                    TextEntry::make('fecha_nac')->label('Fecha de Nacimiento')->color('customgray')->date(),
                                    TextEntry::make('domicilio')->label('Domicilio')->color('customgray'),
                                    TextEntry::make('provincia')->label('Provincia')->color('customgray'),
                                ])->columns(2),
                            ]),

                            Tab::make('Contacto')->schema([
                                Section::make('Datos de Contacto')->schema([
                                    TextEntry::make('email')->label('Correo electrónico')->color('customgray'),
                                    TextEntry::make('telefono')->label('Teléfono')->color('customgray'),
                                ])->columns(2),
                            ]),

                            Tab::make('Datos Académicos')->schema([
                                Section::make('Formación del Becario')->schema([
                                    TextEntry::make('titulo')->label('Título profesional')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('carrera.nombre')->label('Carrera')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Grado')
                                        ->color('customgray'),

                                    TextEntry::make('nivelAcademico.nombre')->label('Nivel Académico')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('disciplina.nombre')->label('Disciplina')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),

                                    TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                                        ->visible(fn ($record) => $record->proyectos->first()?->pivot?->tipo_beca === 'Posgrado')
                                        ->color('customgray'),
                                ])->columns(2),
                            ]),

                            Tab::make('Proyectos')->schema([
                                Section::make('Proyectos Asociados')->schema([
                                    Entry::make('proyectos')
                                        ->label('Proyectos Asociados')
                                        ->view('livewire.proyectos-becarios-list', [
                                            'becario' => $action->getRecord(),
                                        ]),
                                ]),
                            ]),
                        ])
                    ]),
                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->form(fn ($record) => [
                        Grid::make(2)->schema([
                            Select::make('pivot.director_id')
                                ->label('Director')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->required(),
                            Select::make('pivot.codirector_id')
                                ->label('Codirector')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->searchable(),
                            Select::make('pivot.convocatoria_beca_id')
                                ->label('Convocatoria')
                                ->options(ConvocatoriaBeca::all()->pluck('anio', 'id'))
                                ->required(),
                            Select::make('pivot.tipo_beca')
                                ->label('Tipo de Beca')
                                ->options(\App\Models\BecarioProyecto::tiposBeca())
                                ->required(),
                            Toggle::make('pivot.vigente')
                                ->label('Vigente')
                                ->default(true),
                        ]),
                    ])
                    ->mutateRecordDataUsing(function ($data, $record) {
                        // Prellenar los valores desde el pivot
                        return [
                            'pivot.director_id' => $record->pivot->director_id,
                            'pivot.codirector_id' => $record->pivot->codirector_id,
                            'pivot.convocatoria_beca_id' => $record->pivot->convocatoria_beca_id,
                            'pivot.tipo_beca' => $record->pivot->tipo_beca,
                            'pivot.vigente' => $record->pivot->vigente,
                        ];
                    })
                    ->mutateFormDataUsing(function (array $data) {
                        // Solo retornamos los valores que vamos a actualizar
                        return [
                            'director_id' => $data['pivot.director_id'],
                            'codirector_id' => $data['pivot.codirector_id'],
                            'convocatoria_beca_id' => $data['pivot.convocatoria_beca_id'],
                            'tipo_beca' => $data['pivot.tipo_beca'],
                            'vigente' => $data['pivot.vigente'],
                        ];
                    })
                    ->after(function (RelationManager $livewire, $record, array $data) {
                        // Aplicar cambios directamente sobre la pivote
                        $record->pivot->update($data);
                    }),

                DetachAction::make(),
            ]);
    }
}
