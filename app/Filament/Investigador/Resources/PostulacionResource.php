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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('convocatoria_id')
                ->label('Convocatoria')
                ->relationship(
                    'convocatoria',
                    'id',
                    modifyQueryUsing: fn (Builder $query) =>
                        $query->where('estado', true)
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


            Hidden::make('investigador_id')
                ->default(fn () => auth()->user()->investigador?->id),

            Hidden::make('estado')
                ->default('pendiente'),
        ]);
    }

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function canEdit(Model $record): bool
    {
        return $record->estado === 'cargando';
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        // ❌ Sin investigador
        if (! $user || ! $user->investigador) {
            return false;
        }

        // ❌ Si NO hay convocatorias vigentes
        $hayConvocatoriaVigente = ConvocatoriaProyecto::where('estado', true)->exists();

        if (! $hayConvocatoriaVigente) {
            return false;
        }

        // ❌ Si YA tiene alguna postulación (en cualquier estado)
        $yaTienePostulacion = Postulacion::where(
            'investigador_id',
            $user->investigador->id
        )->exists();

        return ! $yaTienePostulacion;
    }



    public static function getPages(): array
    {
        return [
            'index' => ListPostulacions::route('/'),
            'create' => CreatePostulacion::route('/create'),
            'edit' => EditPostulacion::route('/{record}/edit'),
        ];
    }
}
