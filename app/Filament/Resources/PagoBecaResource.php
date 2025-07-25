<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoBecaResource\Pages;
use App\Filament\Resources\PagoBecaResource\RelationManagers;
use App\Models\PagoBeca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\{Section, TextEntry, ViewEntry, Entry, Tabs};
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;

class PagoBecaResource extends Resource
{
    protected static ?string $model = PagoBeca::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Pago Becas';
    protected static ?string $navigationGroup = 'Becas';
    protected static ?string $modelLabel = 'Pago Becas';
    protected static ?string $slug = 'pago-becarios';
    protected static ?int $navigationSort = 2;

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
        Grid::make(2)->schema([
            Select::make('anio')
                ->label('Año')
                ->options(collect(range(now()->year, now()->year - 50))->mapWithKeys(fn ($y) => [$y => $y]))
                ->required(),

            Select::make('mes')
                ->label('Mes')
                ->options([
                    'Enero' => 'Enero', 'Febrero' => 'Febrero', 'Marzo' => 'Marzo',
                    'Abril' => 'Abril', 'Mayo' => 'Mayo', 'Junio' => 'Junio',
                    'Julio' => 'Julio', 'Agosto' => 'Agosto', 'Septiembre' => 'Septiembre',
                    'Octubre' => 'Octubre', 'Noviembre' => 'Noviembre', 'Diciembre' => 'Diciembre',
                ])
                ->required(),

            Select::make('convocatoria_beca_id')
                ->label('Convocatoria')
                ->options(function () {
                    return \App\Models\ConvocatoriaBeca::with('tipoBeca')
                        ->get()
                        ->sortByDesc('anio')
                        ->mapWithKeys(fn ($conv) => [$conv->id => $conv->descripcion])
                        ->toArray();
                })
                ->searchable()
                ->required()
                ->reactive(),

            Select::make('tipo_beca')
                ->label('Tipo de Beca')
                ->options([
                    'Grado' => 'Grado',
                    'Posgrado' => 'Posgrado',
                    'CIN' => 'CIN',
                ])
                ->required()
                ->reactive(),
        ]),
        Repeater::make('becariosPivot')
    ->label('Pagos por Becario')
    ->relationship('becariosPivot') // usa la relación del modelo PagoBeca
    ->schema([
Select::make('becario_id')
    ->label('Becario')
    ->options(function (callable $get, $livewire) {
        $convId = $get('../../convocatoria_beca_id');
        $tipoBeca = $get('../../tipo_beca');

        $currentBecarioId = $get('becario_id');

        $query = \App\Models\Becario::query();

        // Aplica filtro solo si hay convocatoria y tipo
        if ($convId && $tipoBeca) {
            $query->whereHas('proyectos', function ($q) use ($convId, $tipoBeca) {
                $q->where('becario_proyecto.convocatoria_beca_id', $convId)
                    ->where('becario_proyecto.tipo_beca', $tipoBeca)
                    ->where('becario_proyecto.vigente', true);
            });
        }

        // Asegura que se incluya el becario actual aunque no cumpla condiciones
        if ($currentBecarioId) {
            $query->orWhere('id', $currentBecarioId);
        }

        return $query->get()
            ->mapWithKeys(fn($b) => [$b->id => "{$b->apellido}, {$b->nombre}"]);
    })
    ->searchable()
    ->required(),

        TextInput::make('monto')
            ->label('Monto')
            ->numeric()
            ->prefix('$')
            ->required(),
    ])
    ->defaultItems(1)
    ->columnSpanFull(),

    ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anio')
                    ->label('Año'),

                TextColumn::make('mes')
                    ->label('Mes'),

                TextColumn::make('convocatoriaBeca.descripcion')
                    ->label('Convocatoria'),

                TextColumn::make('tipo_beca')
                    ->label('Tipo de Beca')
                    ->getStateUsing(fn ($record) => $record->tipo_beca)
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'Grado' => 'success',
                        'Posgrado' => 'info',
                        'CIN' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state) => $state ?? '—'),

                TextColumn::make('becarios_sum_monto')
                    ->label('Monto Total')
                    ->prefix('$ ')
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.'))

            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('Ver')
                    ->modalHeading(fn ($record) => 'Pago de Becas - ' . $record->anio . ' / ' . $record->mes)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->infolist(fn (ViewAction $action): array => [
                                Section::make('')->schema([
                                    ViewEntry::make('becarios')
                                        ->view('filament.infolists.pagos-expandibles', [
                                            'record' => $action->getRecord(), // solo un pago
                                        ]),
                                ]),
                    ]),

                EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagoBecas::route('/'),
            'create' => Pages\CreatePagoBeca::route('/create'),
            'edit' => Pages\EditPagoBeca::route('/{record}/edit'),
        ];
    }
}
