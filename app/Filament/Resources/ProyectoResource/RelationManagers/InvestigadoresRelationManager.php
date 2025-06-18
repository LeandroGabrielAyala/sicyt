<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Actions\ViewAction;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\DateEntry;
use Filament\Infolists\Components\FileEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Relations\Relation;

class InvestigadoresRelationManager extends RelationManager
{
    protected static string $relationship = 'investigadores';


    protected function getRelationshipQuery(): Builder|Relation
    {
        return parent::getRelationshipQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('investigador_id')
                ->label('Investigador')
                ->relationship('investigador', 'apellido')
                ->getOptionLabelFromRecordUsing(fn ($record) => $record->apellido . ', ' . $record->nombre)
                ->required(),
                
            Select::make('funcion_id')
                ->label('Función')
                ->options(\App\Models\Funcion::query()
                ->pluck('nombre', 'id'))
                ->required(),

            Forms\Components\Toggle::make('vigente')
                ->label('¿Está vigente?')
                ->default(true),

            Forms\Components\DatePicker::make('inicio')
                ->label('Fecha de inicio')
                ->required(),

            Forms\Components\DatePicker::make('fin')
                ->label('Fecha de finalización'),

            Forms\Components\TextInput::make('disposicion')
                ->label('Disposición')
                ->required(),

            Forms\Components\TextInput::make('resolucion')
                ->label('Resolución')
                ->required(),

            Forms\Components\FileUpload::make('pdf_disposicion')
                ->label('Archivo PDF - Disposición')
                ->disk('public')
                ->directory('disposiciones_inv')
                ->preserveFilenames()
                ->multiple()
                ->openable()
                ->downloadable(),

            Forms\Components\FileUpload::make('pdf_resolucion')
                ->label('Archivo PDF - Resolución')
                ->disk('public')
                ->directory('resoluciones_inv')
                ->preserveFilenames()
                ->multiple()
                ->openable()
                ->downloadable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('investigador_id')
            ->columns([
                Tables\Columns\TextColumn::make('investigador_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                ViewAction::make()
            ->label('Ver')
            ->infolist([
                Section::make('investigaores')
                    ->relationship('investigadores')
                    ->schema([
                                TextEntry::make('apellido')->label('Apellido'),
                                TextEntry::make('nombre')->label('Nombre'),
                                TextEntry::make('pivot.funcion.nombre')->label('Función'),
                                IconEntry::make('pivot.vigente')->label('¿Vigente?')->boolean(),
                                TextEntry::make('pivot.inicio')->label('Inicio'),
                                TextEntry::make('pivot.fin')->label('Fin'),
                                TextEntry::make('pivot.disposicion')->label('Disposición'),
                                TextEntry::make('pivot.resolucion')->label('Resolución'),
                                Entry::make('pivot.pdf_disposicion')->label('PDF Disposición')->view('filament.infolists.custom-file-entry-dispo-inv'),
                                Entry::make('pivot.pdf_resolucion')->label('PDF Resolución')->view('filament.infolists.custom-file-entry-reso-inv'),
                    ])
            ]),
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
