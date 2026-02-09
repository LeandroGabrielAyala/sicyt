<?php

namespace App\Filament\Investigador\Resources;

use App\Filament\Investigador\Resources\PostulacionResource\Pages\CreatePostulacion;
use App\Filament\Investigador\Resources\PostulacionResource\Pages\EditPostulacion;
use App\Filament\Investigador\Resources\PostulacionResource\Pages\ListPostulacions;
use App\Models\Postulacion;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\ConvocatoriaProyecto;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;


class PostulacionResource extends Resource
{
    protected static ?string $model = Postulacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Postulaciones';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Postulaciones';
    protected static ?string $slug = 'postulaciones-a-proyectos';
    protected static ?int $navigationSort = 1;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }

    // public static function getNavigationBadgeColor(): string|array|null
    // {
    //     return 'primary';
    // }

    // FORMULARIO
    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('convocatoria_id')
                ->label('Convocatoria')
                ->relationship(
                    'convocatoria',
                    'id',
                    modifyQueryUsing: function (Builder $query, $record) {
                        $investigadorId = auth()->user()->investigador?->id;

                        $yaPostuladas = Postulacion::where('investigador_id', $investigadorId)
                            ->pluck('convocatoria_id');

                        // Siempre permitir la convocatoria del registro actual
                        if ($record) {
                            $yaPostuladas = $yaPostuladas->reject(
                                fn ($id) => $id == $record->convocatoria_id
                            );
                        }

                        $query->where('estado', true)
                            ->whereNotIn('id', $yaPostuladas);
                    }
                )
                ->getOptionLabelFromRecordUsing(
                    fn ($record) =>
                        ($record->tipoProyecto->nombre ?? 'Sin tipo') . ' - ' . $record->anio
                )
                ->required(fn ($record) => $record?->estado !== 'cargando')
                ->columnSpanFull(),


            FileUpload::make('archivo_pdf')
                ->label('Subir documentación probatoria')
                ->multiple()
                ->required(fn ($record) => $record?->estado !== 'cargando')
                ->disk('public')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('postulaciones')
                ->preserveFilenames()
                ->reorderable()
                ->openable()
                ->maxSize(5120)
                ->columnSpanFull(),

            
            Placeholder::make('estado_info')
                ->content('⚠️ Esta postulación aún NO fue enviada. Podés salir y continuar más tarde. 
                Recordá presionar "Guardar" para enviarla definitivamente.')
                ->visible(fn ($record) => $record?->estado === 'cargando'),

            Placeholder::make('observaciones_admin')
                ->label('Observaciones del CEPBBI')
                ->content(fn ($record) => $record?->observaciones ?: '—')
                ->visible(fn ($record) => $record?->estado === 'rechazado')
                ->content(fn ($record) => new HtmlString($record->observaciones))
                ->columnSpanFull(),

            Placeholder::make('resolucion_info')
                ->label('Resolución de aprobación')
                ->visible(fn ($record) => $record?->estado === 'aprobado')
                ->content(function ($record) {

                    $doc = $record->documentaciones()
                        ->where('tipo', 'resolucion')
                        ->first();

                    if (! $doc) {
                        return '—';
                    }

                    return new HtmlString(
                        '<a href="' . asset('storage/' . $doc->archivo) . '" target="_blank" class="text-primary underline">
                            Ver resolución de aprobación
                        </a>'
                    );
                })
                ->columnSpanFull(),

            Hidden::make('investigador_id')
                ->default(fn () => auth()->user()->investigador?->id),

            Hidden::make('estado')
                ->default('pendiente'),
        ]);
    }

    // TABLE
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('convocatoria.tipoProyecto.nombre')->label('Tipo de Proyecto'),
            TextColumn::make('convocatoria.anio')->label('Año de Convocatoria'),
            TextColumn::make('estado')->badge(),
            TextColumn::make('created_at')->date('d/m/Y'),
        ])
        ->actions([
            ViewAction::make()->label('Ver'),
            DeleteAction::make()
                ->label('Eliminar')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Eliminar postulación')
                ->modalDescription('¿Estás seguro de que querés eliminar esta postulación? Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, eliminar')
                ->after(fn () => redirect(request()->header('Referer'))),
        ]);

    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();
        $investigadorId = $user?->investigador?->id;

        if ($investigadorId) {
            $query->where('investigador_id', $investigadorId);
        }

        return $query;
    }

    // RELATION RESOURCE
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // CAN EDIT
    public static function canEdit(Model $record): bool
    {
        // Bloqueamos solo cuando ya fue evaluada por el admin
        return ! in_array($record->estado, ['aprobado', 'rechazado']);
    }

    // CAN VIEW
    public static function canView(Model $record): bool
    {
        return true;
    }

    // CAN CREATE
    public static function canCreate(): bool
    {
        $user = Auth::user();

        if (! $user || ! $user->investigador) {
            return false;
        }

        $investigadorId = $user->investigador->id;

        // IDs de convocatorias vigentes
        $convocatoriasVigentes = ConvocatoriaProyecto::where('estado', true)
            ->pluck('id');

        if ($convocatoriasVigentes->isEmpty()) {
            return false;
        }

        // Convocatorias a las que YA postuló este investigador
        $convocatoriasYaPostuladas = Postulacion::where('investigador_id', $investigadorId)
            ->pluck('convocatoria_id');

        // Convocatorias disponibles = vigentes - ya postuladas
        $convocatoriasDisponibles = $convocatoriasVigentes
            ->diff($convocatoriasYaPostuladas);

        // Si queda al menos una disponible → puede crear
        return $convocatoriasDisponibles->isNotEmpty();
    }

    // PAGES
    public static function getPages(): array
    {
        return [
            'index' => ListPostulacions::route('/'),
            'create' => CreatePostulacion::route('/create'),
            'edit' => EditPostulacion::route('/{record}/edit'),
        ];
    }
}
