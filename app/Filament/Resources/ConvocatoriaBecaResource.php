<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConvocatoriaBecaResource\Pages;
use App\Filament\Resources\ConvocatoriaBecaResource\RelationManagers;
use App\Filament\Resources\ConvocatoriaResource\RelationManagers\BecariosRelationManager;
use App\Models\ConvocatoriaBeca;
use App\Models\TipoBeca;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Entry;
use Filament\Infolists\Components\Tabs\Tab;
use Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction;

class ConvocatoriaBecaResource extends Resource
{
    protected static ?string $model = ConvocatoriaBeca::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Convocatorias de Becas';
    protected static ?string $modelLabel = 'Convocatorias de Becas';
    protected static ?string $navigationGroup = 'Configuración Becas';
    protected static ?string $slug = 'convocatoria-beca';
    protected static ?int $navigationSort = 1;

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
        return $form
            ->schema([
                Select::make('tipo_beca_id')
                    ->relationship('tipoBeca', 'nombre')  // Cambiar aquí 'tipo_beca' por 'tipoBeca'
                    ->required()
                    ->columnSpanFull(),
                Select::make('anio')
                    ->label('Año de convocatoria')
                    ->options(
                        collect(range(now()->year, now()->year - 50))->mapWithKeys(fn ($year) => [$year => $year])
                    )
                    ->required(),
                Toggle::make('estado')
                    ->label('No Vigente / Vigente')
                    ->inline(false)
                    ->required(),
                DatePicker::make('inicio')
                    ->label('Inicio convocatoria')
                    ->required(),
                DatePicker::make('fin')
                    ->label('Fin convocatoria')
                    ->required(),
                TextInput::make('resolucion')
                    ->required()
                    ->maxLength(255),
                TextInput::make('disposicion')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('pdf_resolucion')
                    ->label('Resolución en .PDF')
                    ->multiple()
                    ->required()
                    ->disk('public')
                    ->directory('resoluciones_becas')
                    ->preserveFilenames()
                    ->reorderable()
                    ->openable(),
                FileUpload::make('pdf_disposicion')
                    ->label('Disposición en .PDF')
                    ->multiple()
                    ->required()
                    ->disk('public')
                    ->directory('disposiciones_becas')
                    ->preserveFilenames()
                    ->reorderable()
                    ->openable(),
            ])->columns(2);
    }

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('tipoBeca.nombre')->label('Tipo')->searchable(),
            TextColumn::make('anio')->label('Año')->sortable(),
            TextColumn::make('inicio')->label('Inicio')->date()->sortable()->searchable(),
            TextColumn::make('fin')->label('Fin')->date()->sortable()->searchable(),
            IconColumn::make('estado')->label('Estado')->boolean(),
        ])
        ->actions([
            MediaAction::make('ver_resolucion')
                ->label(fn ($record) => $record->resolucion ?? 'Sin Resolución')
                ->icon('heroicon-o-document-arrow-down')
                ->media(fn ($record) => $record->pdf_resolucion
                    ? asset('storage/' . $record->pdf_resolucion[0])
                    : null
                ),
            ViewAction::make('view')
                ->label('Ver')
                ->modalHeading(fn (ConvocatoriaBeca $record) => 'Detalles de Convocatoria ' . $record->anio)
                ->modalSubmitAction(false)
                ->modalCancelAction(fn () => null)
                ->modalCancelActionLabel('Cerrar')
                ->infolist(fn (ViewAction $action): array => [
                    Tabs::make('Tabs')
                        ->tabs([
                            Tab::make('Datos Generales')
                                ->schema([
                                    Section::make('Información básica')
                                        ->schema([
                                            TextEntry::make('tipoBeca.nombre')
                                                ->label('Tipo de Beca')
                                                ->color('customgray'),
                                            TextEntry::make('anio')
                                                ->label('Año')
                                                ->color('customgray'),
                                            TextEntry::make('inicio')
                                                ->label('Fecha de Inicio')
                                                ->color('customgray'),
                                            TextEntry::make('fin')
                                                ->label('Fecha de Fin')
                                                ->color('customgray'),
                                            TextEntry::make('estado')
                                                ->label('Estado')
                                                ->color('customgray')
                                                ->formatStateUsing(fn ($state) => $state ? 'Vigente' : 'No Vigente'),
                                        ])->columns(2),
                                ]),

                            Tab::make('Documentos')
                                ->schema([
                                    Section::make('Documentación')
                                        ->schema([
                                            TextEntry::make('resolucion')->label('Nro. de Resolución')
                                                ->color('customgray'),
                                            TextEntry::make('disposicion')->label('Nro. de Disposición')
                                                ->color('customgray'),
                                            Entry::make('pdf_resolucion')
                                                ->label('Resoluciones en PDF')
                                                ->view('filament.infolists.convocatoria-beca-reso'),
                                            Entry::make('pdf_disposicion')
                                                ->label('Disposiciones en PDF')
                                                ->view('filament.infolists.convocatoria-beca-dispo'),
                                        ])->columns(2),
                                    ]),
                        ])
                ]),
            EditAction::make()
        ])
        ->filters([
            //
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

    public static function getRelations(): array
    {
        return [
            BecariosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConvocatoriaBecas::route('/'),
            'create' => Pages\CreateConvocatoriaBeca::route('/create'),
            'edit' => Pages\EditConvocatoriaBeca::route('/{record}/edit'),
        ];
    }
}
