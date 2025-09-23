<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PostulacionResource\Pages;
use App\Models\Postulacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PostulacionResource extends Resource
{
    protected static ?string $model = Postulacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Postulaciones';
    protected static ?string $navigationGroup = 'Proyectos';
    protected static ?string $modelLabel = 'Postulaciones';
    protected static ?string $slug = 'postulaciones-a-proyectos';
    protected static ?int $navigationSort = 1;

    // Mostrar navegación solo si el usuario tiene un investigador asociado
    public static function canViewForNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->investigador?->id !== null;
    }

    public static function authorizeResource(): void
    {
        static::authorizeUsing(fn ($user) => $user && $user->investigador?->id !== null);
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('convocatoria_id')
                ->label('Convocatoria')
                ->relationship('convocatoria', 'id')
                ->getOptionLabelFromRecordUsing(fn ($record) => ($record->tipoProyecto->nombre ?? 'Sin tipo') . ' - ' . $record->anio)
                ->required(),

            FileUpload::make('archivo_pdf')
                ->label('Subir PDF unificado')
                ->acceptedFileTypes(['application/pdf'])
                ->directory('postulaciones')
                ->maxSize(5120)
                ->required(),

            Hidden::make('investigador_id')
                ->default(fn () => auth()->user()->investigador?->id),

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
        $data['investigador_id'] = auth()->user()->investigador?->id;
        $data['estado'] = 'pendiente';
        return $data;
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostulacions::route('/'),
            'create' => Pages\CreatePostulacion::route('/create'),
            'edit' => Pages\EditPostulacion::route('/{record}/edit'),
        ];
    }
}
