<?php

namespace App\Filament\Resources\InvestigadorResource\RelationManagers;

use App\Models\Becario;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Tabs as InfoTabs;
use Filament\Infolists\Components\Tabs\Tab as InfoTab;
use Filament\Infolists\Components\Section as InfoSection;

class BecariosRelationManager extends RelationManager
{
    protected static string $relationship = 'becariosComoDirector'; // nombre de la relación del modelo
    protected static ?string $title = 'Becarios a Cargo';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->heading('Becarios que tiene cargo como Director/Co-director.')
            ->query(
                fn (): Builder => \App\Models\BecarioProyecto::query()
                    ->where(function ($query) {
                        $query->where('director_id', $this->ownerRecord->id)
                              ->orWhere('codirector_id', $this->ownerRecord->id);
                    })->with(['becario', 'proyecto', 'convocatoria'])
            )
            ->columns([
                TextColumn::make('becario.apellido')->label('Apellido'),
                TextColumn::make('becario.nombre')->label('Nombre'),
                TextColumn::make('proyecto.nro')->label('Proyecto'),
                TextColumn::make('convocatoria.tipoBeca.nombre')->label('Tipo Beca'),
                TextColumn::make('convocatoria.anio')->label('Año Conv.'),
                TextColumn::make('tipo_beca')->label('Tipo'),
                TextColumn::make('plan_trabajo')->label('Plan Trabajo')->limit(30),
                TextColumn::make('vigente')->label('Vigente')->formatStateUsing(fn($state) => $state ? '✔️' : '❌'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Becario ' . $record->becario?->nombre_completo)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn ($action): array => [
                        InfoTabs::make('Tabs')->tabs([
                            InfoTab::make('Datos Personales')->schema([
                                InfoSection::make('Información personal')->schema([
                                    TextEntry::make('becario.apellido')->label('Apellido'),
                                    TextEntry::make('becario.nombre')->label('Nombre'),
                                    TextEntry::make('becario.dni')->label('DNI'),
                                    TextEntry::make('becario.cuil')->label('CUIL'),
                                    TextEntry::make('becario.fecha_nac')->label('Fecha de nacimiento')->date(),
                                    TextEntry::make('becario.lugar_nac')->label('Lugar de nacimiento'),
                                    TextEntry::make('becario.domicilio')->label('Domicilio'),
                                    TextEntry::make('becario.provincia')->label('Provincia'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Contacto')->schema([
                                InfoSection::make('Medios de contacto')->schema([
                                    TextEntry::make('becario.email')->label('Email'),
                                    TextEntry::make('becario.telefono')->label('Teléfono'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Académico')->schema([
                                InfoSection::make('Formación académica')->schema([
                                    TextEntry::make('becario.titulo')->label('Título profesional'),
                                    TextEntry::make('becario.carrera.nombre')->label('Carrera'),
                                    TextEntry::make('becario.nivelAcademico.nombre')->label('Nivel Académico'),
                                    TextEntry::make('becario.campo.nombre')->label('Campo de aplicación'),
                                    TextEntry::make('becario.disciplina.nombre')->label('Disciplina'),
                                    TextEntry::make('becario.objetivo.nombre')->label('Objetivo socioeconómico'),
                                ])->columns(2),
                            ]),

                            InfoTab::make('Beca')->schema([
                                InfoSection::make('Información de la beca')->schema([
                                    TextEntry::make('convocatoria.tipoBeca.nombre')->label('Tipo Beca'),
                                    TextEntry::make('convocatoria.anio')->label('Año Convocatoria'),
                                    TextEntry::make('tipo_beca')->label('Tipo'),
                                    TextEntry::make('plan_trabajo')->label('Plan de Trabajo')->columnSpanFull(),
                                    TextEntry::make('vigente')->label('¿Vigente?')->formatStateUsing(fn($state) => $state ? '✔️ Sí' : '❌ No'),
                                    TextEntry::make('proyecto.nro')->label('Proyecto asociado'),
                                ])->columns(2),
                            ]),
                        ]),
                    ]),
            ]);
    }
}
