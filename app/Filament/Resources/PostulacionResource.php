<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostulacionResource\Pages;
use App\Models\Postulacion;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostulacionResource extends Resource
{
    protected static ?string $model = Postulacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Postulaciones';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Postulaciones';
    protected static ?string $slug = 'postulaciones-a-proyectos';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary'; //return static::getModel()::count() > 5 ? 'primary' : 'warning';
    }

    // FORMULARIO
    public static function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('convocatoria')
                ->label('Convocatoria')
                ->content(fn ($record) =>
                    $record->convocatoria
                        ? ($record->convocatoria->tipoProyecto->nombre . ' - ' . $record->convocatoria->anio)
                        : '—'
                ),

            Placeholder::make('investigador')
                ->label('Investigador')
                ->content(fn ($record) =>
                    $record->investigador
                        ? $record->investigador->apellido . ', ' . $record->investigador->nombre
                        : '—'
                ),


            FileUpload::make('archivo_pdf')
                ->label('Formularios PDF')
                ->multiple()                // 👈 CLAVE ABSOLUTA
                ->downloadable()
                ->openable()
                ->disabled()
                ->columnSpanFull(),

            Select::make('estado')
                ->options([
                    'aprobado' => 'Aprobado',
                    'rechazado' => 'Rechazado',
                ])
                ->required()
                ->disabled(fn ($record) => $record?->estado !== 'pendiente'),


            RichEditor::make('observaciones')
                ->label('Observaciones del CEPPBI')
                ->columnSpanFull()
                ->required(fn ($get) => $get('estado') === 'rechazado')
                ->disabled(fn ($record) => $record?->estado !== 'pendiente'),

        ]);
    }

    // TABLE
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('convocatoria.tipoProyecto.nombre')->label('Tipo de Proyecto'),
            TextColumn::make('convocatoria.anio')->label('Año de Convocatoria'),
            TextColumn::make('investigador')
                ->label('Investigador')
                ->getStateUsing(fn ($record) => $record->investigador?->apellido . ', ' . $record->investigador?->nombre),
            TextColumn::make('estado')->badge(),
            TextColumn::make('created_at')->date('d/m/Y'),
        ])
        ->actions([
            ViewAction::make()->label('Ver'),

            // ✅ APROBAR
            Action::make('aprobar')
                ->label('Aprobar')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn ($record) => $record->estado === 'pendiente')
                ->form([
                    FileUpload::make('resolucion_pdf')
                        ->label('Resolución de aprobación (PDF)')
                        ->required()
                        ->disk('public')
                        ->directory('resoluciones')
                        ->acceptedFileTypes(['application/pdf'])
                        ->maxSize(5120),
                ])
                ->action(function ($record, array $data) {

                    // 1️⃣ Guardar documento en tabla documentaciones
                    $path = $data['resolucion_pdf'];

                    $record->documentaciones()->create([
                        'nombre' => 'Resolución de aprobación',
                        'archivo' => $path,
                        'tipo' => 'resolucion',
                        'fecha' => now(),
                    ]);

                    // 2️⃣ Cambiar estado
                    $record->update([
                        'estado' => 'aprobado',
                    ]);
                })
                ->requiresConfirmation()
                ->modalHeading('Aprobar postulación')
                ->modalDescription('Debés cargar obligatoriamente la resolución de aprobación.'),

            // ❌ RECHAZAR
            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn ($record) => $record->estado === 'pendiente')
                ->form([
                    RichEditor::make('observaciones')
                        ->label('Justificación del rechazo')
                        ->required()
                        ->columnSpanFull(),
                ])
                ->action(function ($record, array $data) {

                    // 1️⃣ Guardar observaciones
                    $record->update([
                        'estado' => 'rechazado',
                        'observaciones' => $data['observaciones'],
                    ]);

                })
                ->requiresConfirmation()
                ->modalHeading('Rechazar postulación')
                ->modalDescription('Debés indicar el motivo del rechazo.'),
        ]);

    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('estado', '!=', 'cargando');
    }

    // CAN EDIT
    public static function canEdit(Model $record): bool
    {
        return $record->estado === 'pendiente';
    }

    // RELATION RESOURCE
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // PAGES
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostulacions::route('/'),
            'create' => Pages\CreatePostulacion::route('/create'),
            'edit' => Pages\EditPostulacion::route('/{record}/edit'),
        ];
    }
}