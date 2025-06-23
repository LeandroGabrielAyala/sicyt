<?php

namespace App\Filament\Resources;

use App\Models\Becario;
use App\Models\TipoBeca;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class BecarioResource extends Resource
{
    protected static ?string $model = Becario::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('nombre')->required(),
                    TextInput::make('apellido')->required(),
                    TextInput::make('dni')->required()->maxLength(10)->unique(ignoreRecord: true),
                    TextInput::make('cuil')->required()->maxLength(15)->unique(ignoreRecord: true),
                    DatePicker::make('fecha_nac')->required(),
                    TextInput::make('domicilio')->required(),
                    TextInput::make('provincia')->required(),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('telefono')->required()->maxLength(20)->unique(ignoreRecord: true),
                    Select::make('tipo_beca_id')
                        ->relationship('tipo_beca', 'nombre')
                            ->options(TipoBeca::orderBy('nombre', 'desc')->pluck('nombre', 'id')
                        )
                        ->required()
                        ->columnSpanFull()
                        ->live(),
                    Textarea::make('plan_trabajo')->required()->columnSpanFull(),
                    TextInput::make('pago')->required()->numeric(),

                    Select::make('carrera_id')
                        ->relationship('carrera', 'nombre')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Grado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Grado'),

                    Select::make('nivel_academico_id')
                        ->relationship('nivelAcademico', 'nombre')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                    Select::make('disciplina_id')
                        ->relationship('disciplina', 'nombre')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                    Select::make('campo_id')
                        ->relationship('campo', 'nombre')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                    Select::make('objetivo_id')
                        ->relationship('objetivo', 'nombre')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),

                    TextInput::make('titulo')
                        ->required(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado')
                        ->visible(fn (callable $get) => TipoBeca::find($get('tipo_beca_id'))?->nombre === 'UNCAUS Posgrado'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('apellido')->sortable()->searchable(),
                // Tables\Columns\TextColumn::make('nombre')->sortable()->searchable(),
                // Tables\Columns\TextColumn::make('tipo'),
                // Tables\Columns\TextColumn::make('dni'),
                // Tables\Columns\TextColumn::make('email')->limit(25),
            ])
            ->actions([
                // ViewAction::make(),
                // EditAction::make(),
                // DeleteAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Section::make('Datos Personales')->schema([
                //     TextEntry::make('nombre'),
                //     TextEntry::make('apellido'),
                //     TextEntry::make('dni'),
                //     TextEntry::make('cuil'),
                //     TextEntry::make('fecha_nac')->date(),
                //     TextEntry::make('email'),
                //     TextEntry::make('telefono'),
                //     TextEntry::make('domicilio'),
                //     TextEntry::make('provincia'),
                // ])->columns(2),

                // Section::make('Datos de la Beca')->schema([
                //     TextEntry::make('tipo')->label('Tipo de Beca'),
                //     TextEntry::make('plan_trabajo')->label('Plan de trabajo')->columnSpanFull(),
                //     TextEntry::make('pago')->money('ARS'),
                // ])->columns(2),

                // Section::make('Datos Académicos')->schema([
                //     TextEntry::make('carrera.nombre')->label('Carrera')
                //         ->visible(fn ($record) => $record->tipo === 'Grado'),

                //     TextEntry::make('titulo')->label('Título profesional')
                //         ->visible(fn ($record) => $record->tipo === 'Posgrado'),

                //     TextEntry::make('nivelAcademico.nombre')->label('Nivel Académico')
                //         ->visible(fn ($record) => $record->tipo === 'Posgrado'),

                //     TextEntry::make('disciplina.nombre')->label('Disciplina')
                //         ->visible(fn ($record) => $record->tipo === 'Posgrado'),

                //     TextEntry::make('campo.nombre')->label('Campo de Aplicación')
                //         ->visible(fn ($record) => $record->tipo === 'Posgrado'),

                //     TextEntry::make('objetivo.nombre')->label('Objetivo Socioeconómico')
                //         ->visible(fn ($record) => $record->tipo === 'Posgrado'),
                // ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRecords::route('/'),
            'create' => CreateRecord::route('/create'),
            'view' => ViewRecord::route('/{record}'),
            'edit' => EditRecord::route('/{record}/edit'),
        ];
    }
}
