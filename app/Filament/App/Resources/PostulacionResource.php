<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PostulacionResource\Pages;
use App\Filament\App\Resources\PostulacionResource\RelationManagers;
use App\Models\Postulacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class PostulacionResource extends Resource
{
    protected static ?string $model = Postulacion::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('convocatoria_id')
                ->label('Convocatoria')
                ->relationship('convocatoria', 'id') // usa el id como clave real
                ->getOptionLabelFromRecordUsing(fn ($record) => ($record->tipoProyecto->nombre ?? 'Sin tipo') . ' - ' . $record->anio)
                ->required(),


            FileUpload::make('archivo_pdf')
                ->label('Subir PDF unificado')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('postulaciones')
                ->maxSize(5120) // 5MB
                ->required(),

            Hidden::make('investigador_id')
                ->default(fn () => auth()->id()),

            Hidden::make('estado')
                ->default('pendiente'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('convocatoria.titulo')->label('Convocatoria'),
            TextColumn::make('estado')->badge(),
            TextColumn::make('created_at')->date('d/m/Y'),
        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['investigador_id'] = auth()->id();
        $data['estado'] = 'pendiente';
        return $data;
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
