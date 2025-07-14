<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Adscripto;
use App\Models\ConvocatoriaAdscripto;
use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Tables\Columns\IconColumn;

class AdscriptosRelationManager extends RelationManager
{
    protected static string $relationship = 'adscriptos';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')
                    ->label('Nombre completo')
                    ->formatStateUsing(fn ($state, $record) => $record->apellido . ', ' . $record->nombre),
                TextColumn::make('pivot.director_id')
                    ->label('Director')
                    ->formatStateUsing(fn ($state) => Investigador::find($state)?->nombre_completo ?? '—'),
                TextColumn::make('pivot.codirector_id')
                    ->label('Codirector')
                    ->formatStateUsing(fn ($state) => $state ? Investigador::find($state)?->nombre_completo : '—'),
                IconColumn::make('pivot.vigente')
                    ->label('Vigente')
                    ->boolean(),
                TextColumn::make('pivot.convocatoria.anio')
                    ->label('Convocatoria')
                    ->badge()
                    ->color('gray'),

            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Asociar')
                    ->form([
                        Grid::make(2)->schema([
                            Select::make('recordId')
                                ->label('Adscripto')
                                ->options(Adscripto::all()->pluck('nombre_completo', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('director_id')
                                ->label('Director')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->searchable()
                                ->required(),
                            Select::make('codirector_id')
                                ->label('Codirector')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->searchable(),
                            Select::make('convocatoria_adscripto_id')
                                ->label('Convocatoria')
                                ->options(ConvocatoriaAdscripto::all()->pluck('anio', 'id'))
                                ->searchable()
                                ->required(),
                            Toggle::make('vigente')
                                ->label('Vigente')
                                ->default(true),
                        ]),
                    ]),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Adscripto ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action) => [
                        Tabs::make('Detalles')->tabs([
                            Tab::make('Personales')->schema([
                                Section::make()->schema([
                                    TextEntry::make('apellido')->label('Apellido(s)')->color('gray'),
                                    TextEntry::make('nombre')->label('Nombre(s)')->color('gray'),
                                    TextEntry::make('dni')->label('DNI')->color('gray'),
                                    TextEntry::make('cuil')->label('CUIL')->color('gray'),
                                    TextEntry::make('fecha_nac')->label('Fecha de nacimiento')->color('gray')->date(),
                                    TextEntry::make('lugar_nac')->label('Lugar de nacimiento')->color('gray'),
                                    TextEntry::make('domicilio')->label('Domicilio')->color('gray'),
                                    TextEntry::make('provincia')->label('Provincia')->color('gray'),
                                ])->columns(3),
                            ]),
                            Tab::make('Contacto')->schema([
                                Section::make()->schema([
                                    TextEntry::make('email')->label('Correo electrónico')->color('gray'),
                                    TextEntry::make('telefono')->label('Teléfono')->color('gray'),
                                ])->columns(2),
                            ]),
                            Tab::make('Formación')->schema([
                                Section::make()->schema([
                                    TextEntry::make('carrera.nombre')->label('Carrera')->color('gray'),
                                    TextEntry::make('titulo.nombre')->label('Título')->color('gray'),
                                ])->columns(2),
                            ]),
                        ])
                    ]),
                
                    EditAction::make()
                    ->label('Editar')
                    ->form(fn ($record) => [
                        Grid::make(2)->schema([
                            Select::make('director_id')
                                ->label('Director')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->required(),

                            Select::make('codirector_id')
                                ->label('Codirector')
                                ->options(Investigador::all()->pluck('nombre_completo', 'id'))
                                ->searchable(),

                            Select::make('convocatoria_adscripto_id')
                                ->label('Convocatoria')
                                ->options(ConvocatoriaAdscripto::all()->pluck('anio', 'id'))
                                ->required(),

                            Toggle::make('vigente')
                                ->label('Vigente')
                                ->default(true),
                        ]),
                    ])
                    ->mutateRecordDataUsing(fn ($data, $record) => [
                        'director_id' => $record->pivot->director_id,
                        'codirector_id' => $record->pivot->codirector_id,
                        'convocatoria_adscripto_id' => $record->pivot->convocatoria_adscripto_id,
                        'vigente' => $record->pivot->vigente,
                    ])
                    ->after(function (RelationManager $livewire, $record, array $data) {
                        $record->pivot->update([
                            'director_id' => $data['director_id'],
                            'codirector_id' => $data['codirector_id'],
                            'convocatoria_adscripto_id' => $data['convocatoria_adscripto_id'],
                            'vigente' => $data['vigente'],
                        ]);
                    }),

                DetachAction::make()->label('Quitar'),
            ]);
    }
}
