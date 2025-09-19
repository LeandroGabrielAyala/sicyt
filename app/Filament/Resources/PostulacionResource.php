<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostulacionResource\Pages;
use App\Models\Postulacion;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PostulacionResource extends Resource
{
    protected static ?string $model = Postulacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    // protected static ?string $navigationGroup = 'Convocatorias';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('convocatoria.titulo')
                ->label('Convocatoria')
                ->disabled(),

            TextInput::make('investigador.nombre')
                ->label('Investigador')
                ->disabled(),

            FileUpload::make('archivo_pdf')
                ->label('Formulario PDF')
                ->downloadable()
                ->disabled(),

            Select::make('estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'aprobado' => 'Aprobado',
                    'rechazado' => 'Rechazado',
                ])
                ->required(),

            Textarea::make('observaciones')
                ->label('Observaciones del Admin'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('convocatoria.titulo')->label('Convocatoria'),
            TextColumn::make('investigador.nombre')->label('Investigador'),
            TextColumn::make('estado')->badge(),
            TextColumn::make('created_at')->date('d/m/Y'),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostulacions::route('/'),
            'create' => Pages\CreatePostulacion::route('/create'),
            'edit' => Pages\EditPostulacion::route('/{record}/edit'),
        ];
    }
}