<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class InvestigadorRelationManager extends RelationManager
{
    protected static string $relationship = 'investigador'; // importante que coincida con el nombre del método en Proyecto.php

    protected static ?string $title = 'Investigadores asociados';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre'),
                TextColumn::make('apellido'),
                TextColumn::make('pivot.funcion.nombre')->label('Función'),
                TextColumn::make('pivot.inicio')->date()->label('Inicio'),
                TextColumn::make('pivot.fin')->date()->label('Fin'),
                IconColumn::make('pivot.vigente')->label('Vigente')->boolean(),
            ])
            ->headerActions([
                AttachAction::make()->label('Asociar')
                    ->form([
                        Select::make('recordId')
                            ->label('Investigador')
                            ->options(\App\Models\Investigador::all()->pluck('nombre_completo', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('funcion_id')
                            ->label('Función')
                            ->options(\App\Models\Funcion::orderBy('nombre')->pluck('nombre', 'id'))
                            ->searchable()
                            ->required(),
                        DatePicker::make('inicio')
                            ->label('Inicio')
                            ->required(),
                        DatePicker::make('fin')
                            ->label('Fin'),
                        FileUpload::make('pdf_disposicion')
                            ->label('PDF Disposición')
                            ->disk('public')
                            ->directory('disposiciones')
                            ->acceptedFileTypes(['application/pdf'])
                            ->multiple()
                            ->maxFiles(1),
                        FileUpload::make('pdf_resolucion')
                            ->label('PDF Resolución')
                            ->disk('public')
                            ->directory('resoluciones')
                            ->acceptedFileTypes(['application/pdf'])
                            ->multiple()
                            ->maxFiles(1),
                        Toggle::make('vigente')
                            ->label('Vigente')
                            ->default(true),
                    ]),
            ])

            ->actions([
                DetachAction::make()->label('Quitar'), // permite desasociar
                ViewAction::make()->label('Ver'),   // opcional: ver detalles
            ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // No queremos formulario para crear investigadores nuevos desde acá
    }
}
