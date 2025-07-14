<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Funcion;
use App\Models\Investigador;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
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

    protected static ?string $title = 'Investigadores';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido'),
                TextColumn::make('nombre'),
                TextColumn::make('dni'),
                TextColumn::make('pivot.funcion.nombre')->label('Función'),
                TextColumn::make('pivot.inicio')->date()->label('Inicio'),
                TextColumn::make('pivot.fin')->date()->label('Fin'),
                IconColumn::make('pivot.vigente')->label('Vigente')->boolean(),
            ])

            ->headerActions([
                AttachAction::make()->label('Asociar')
                    ->form([
                        Grid::make(2)->schema([
                            Select::make('recordId')
                                ->label('Investigador')
                                ->options(function () {
                                    $idsInvestigadores = $this->ownerRecord->investigador()->pluck('investigadors.id')->toArray();

                                    $directorId = Funcion::where('nombre', 'Director')->value('id');
                                    $codirectorId = Funcion::where('nombre', 'Co-director')->value('id');

                                    $investigadoresDirectores = $this->ownerRecord->investigador()
                                        ->wherePivotIn('funcion_id', [$directorId, $codirectorId])
                                        ->pluck('investigadors.id')
                                        ->toArray();

                                    $excluirIds = array_unique(array_merge($idsInvestigadores, $investigadoresDirectores));

                                    return Investigador::whereNotIn('id', $excluirIds)
                                        ->get()
                                        ->pluck('apellido_nombre', 'id');
                                })
                                ->searchable()
                                ->required(),

                            Select::make('funcion_id')
                                ->label('Función')
                                ->options(function () {
                                    $directorId = Funcion::where('nombre', 'Director')->value('id');
                                    $codirectorId = Funcion::where('nombre', 'Co-director')->value('id');

                                    $tieneDirector = $this->ownerRecord->investigador()->wherePivot('funcion_id', $directorId)->exists();
                                    $tieneCodirector = $this->ownerRecord->investigador()->wherePivot('funcion_id', $codirectorId)->exists();

                                    $funciones = Funcion::orderBy('nombre')->pluck('nombre', 'id')->toArray();

                                    if ($tieneDirector) {
                                        unset($funciones[$directorId]);
                                    }
                                    if ($tieneCodirector) {
                                        unset($funciones[$codirectorId]);
                                    }

                                    return $funciones;
                                })
                                ->searchable()
                                ->required(),

                            DatePicker::make('inicio')
                                ->label('Inicio')
                                ->required(),

                            DatePicker::make('fin')
                                ->label('Fin'),
                                
                            TextInput::make('disposicion')
                                ->label('N° de Disposición')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            FileUpload::make('pdf_disposicion')
                                ->label('PDF Disposición')
                                ->required()
                                ->disk('public')
                                ->directory('disposiciones_inv')
                                ->acceptedFileTypes(['application/pdf'])
                                ->multiple()
                                ->preserveFilenames()
                                ->reorderable()
                                ->openable()
                                ->columnSpanFull(),

                            TextInput::make('resolucion')
                                ->label('N° de Resolución')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            FileUpload::make('pdf_resolucion')
                                ->label('PDF Resolución')
                                ->required()
                                ->disk('public')
                                ->directory('resoluciones_inv')
                                ->acceptedFileTypes(['application/pdf'])
                                ->multiple()
                                ->preserveFilenames()
                                ->reorderable()
                                ->openable()
                                ->columnSpanFull(),

                            Toggle::make('vigente')
                                ->label('Vigente')
                                ->default(true),
                        ]),
                    ])
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data, RelationManager $livewire) {
                        $livewire->getRelationship()->attach(
                            $data['recordId'],
                            [
                                'funcion_id' => $data['funcion_id'],
                                'inicio' => $data['inicio'],
                                'fin' => $data['fin'],
                                'resolucion' => $data['resolucion'],
                                'disposicion' => $data['disposicion'],
                                'pdf_disposicion' => $data['pdf_disposicion'],
                                'pdf_resolucion' => $data['pdf_resolucion'],
                                'vigente' => $data['vigente'] ?? true,
                            ]
                        );
                    }),
            ])


            ->actions([
                ViewAction::make()->label('Ver')
                    ->modalHeading(fn ($record) => 'Detalles del Investigador ' . $record->nombre . ' ' . $record->apellido)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn ($record) => [
                        Tabs::make('Tabs')
                            ->tabs([
                                Tab::make('Particiáción en Proyecto')->schema([
                                    Section::make('Detalle del Investigador en los PI.')
                                        ->schema([
                                            TextEntry::make('pivot.funcion.nombre')->label('Función'),
                                            TextEntry::make('pivot.vigente')
                                                ->label('Vigente / No Vigente')
                                                ->badge()
                                                ->color(fn (bool $state) => $state ? 'success' : 'danger')
                                                ->formatStateUsing(fn (bool $state) => $state ? 'Vigente' : 'No Vigente'),  
                                            TextEntry::make('pivot.inicio')->label('Inicio')->date(),
                                            TextEntry::make('pivot.fin')->label('Fin')->date(),
                                            Entry::make('pivot.pdf_disposicion')
                                                ->label('Disposiciones en PDF')
                                                ->view('filament.infolists.custom-file-entry-dispo-inv'),

                                            Entry::make('pivot.pdf_resolucion')
                                                ->label('Resoluciones en PDF')
                                                ->view('filament.infolists.custom-file-entry-reso-inv'),
                                        ])->columns(2),
                                ]),
                                Tab::make('Datos Generales')->schema([
                                    Section::make('Información del Investigador')
                                        ->schema([
                                            TextEntry::make('nombre')->label('Nombre(s)')->color('customgray'),
                                            TextEntry::make('apellido')->label('Apellido(s)')->color('customgray'),
                                            TextEntry::make('categoriaInterna.categoria')->label('Categoría Interna UNCAUS')->color('customgray'),
                                            TextEntry::make('incentivo.categoria')->label('Categoría de Incentivo')->color('customgray'),
                                            TextEntry::make('titulo')->label('Título profesional')->color('customgray'),
                                            TextEntry::make('titulo_posgrado')->label('Título de posgrado')->color('customgray'),
                                        ])->columns(2),
                                ]),
                                Tab::make('Clasificación')->schema([
                                    Section::make('Clasificación del Investigador según RACT')
                                        ->schema([
                                            TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')->color('customgray'),
                                            TextEntry::make('campo.nombre')->label('Campo de Aplicación')->color('customgray'),
                                            TextEntry::make('nivelAcademico.nombre')->label('Nivel Académico')->color('customgray'),
                                        ])->columns(2),
                                ]),
                                Tab::make('Contacto')->schema([
                                    Section::make('Datos de Contacto')
                                        ->schema([
                                            TextEntry::make('email')->label('Correo electrónico')->color('customgray'),
                                            TextEntry::make('telefono')->label('Teléfono')->color('customgray'),
                                        ])->columns(2),
                                ]),
                                Tab::make('Datos Personales')->schema([
                                    Section::make('Datos personales del Investigador')
                                        ->schema([
                                            TextEntry::make('dni')->label('DNI')->color('customgray'),
                                            TextEntry::make('cuil')->label('CUIL')->color('customgray'),
                                            TextEntry::make('fecha_nac')->label('Fecha de Nacimiento')->color('customgray'),
                                            TextEntry::make('domicilio')->label('Domicilio')->color('customgray'),
                                            TextEntry::make('provincia')->label('Provincia')->color('customgray'),
                                        ])->columns(2),
                                ]),
                            ])
                        ]),                
                EditAction::make()
                    ->label('Editar')
                    ->form([
                        Grid::make(2)->schema([
                            Select::make('funcion_id')->label('Función')
                                ->options(fn () => \App\Models\Funcion::orderBy('nombre')->pluck('nombre', 'id'))
                                ->required(),
                            Toggle::make('vigente')->label('Vigente')->default(true),
                            DatePicker::make('inicio')->label('Inicio')->required(),
                            DatePicker::make('fin')->label('Fin'),
                            TextInput::make('resolucion')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('disposicion')
                                ->required()
                                ->maxLength(255),
                            FileUpload::make('pdf_disposicion')->label('PDF Disposición')
                                ->disk('public')->directory('disposiciones_inv')
                                ->acceptedFileTypes(['application/pdf'])->multiple()
                                ->preserveFilenames()->reorderable()->openable(),
                            FileUpload::make('pdf_resolucion')->label('PDF Resolución')
                                ->disk('public')->directory('resoluciones_inv')
                                ->acceptedFileTypes(['application/pdf'])->multiple()
                                ->preserveFilenames()->reorderable()->openable(),
                        ]),
                    ])
                    ->mutateRecordDataUsing(function ($data, $record) {
                        $data['pdf_disposicion'] = $record->pivot->pdf_disposicion;
                        $data['pdf_resolucion'] = $record->pivot->pdf_resolucion;
                        return $data;
                    })
                    ->mutateFormDataUsing(function (array $data, $record) {
                        return [
                            'funcion_id' => $data['funcion_id'],
                            'inicio' => $data['inicio'],
                            'fin' => $data['fin'],
                            'vigente' => $data['vigente'],
                            'pdf_disposicion' => $data['pdf_disposicion'] ?? $record->pivot->pdf_disposicion,
                            'pdf_resolucion' => $data['pdf_resolucion'] ?? $record->pivot->pdf_resolucion,
                        ];
                    })
                    ->after(function (RelationManager $livewire, $record, array $data) {
                        $record->pivot->update($data);
                    }),
                DetachAction::make()->label('Quitar'), // permite desasociar

            ]);
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form; // No queremos formulario para crear investigadores nuevos desde acá
    }
}
