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

class PagoBecaResource extends Resource
{
    protected static ?string $model = PagoBeca::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                        ->sortByDesc('anio') // ordena por año si querés
                        ->pluck('descripcion', 'id'); // usa el accesor
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

        Repeater::make('becarios')
            ->label('Pagos por Becario')
            ->relationship()
            ->schema([
            Select::make('becario_id')
                ->label('Becario')
                ->options(function (callable $get) {
                    $convId = $get('../../convocatoria_beca_id');
                    if (!$convId) {
                        return [];
                    }
                    return \App\Models\Becario::whereHas('proyectos', function ($q) use ($convId) {
                        $q->where('becario_proyecto.convocatoria_beca_id', $convId)
                        ->where('becario_proyecto.vigente', true);
                    })->get()->mapWithKeys(fn($b) => [$b->id => "{$b->apellido}, {$b->nombre}"]);
                })
                ->searchable()
                ->required(),
            TextInput::make('monto')
                ->label('Monto')
                ->numeric()
                ->prefix('$')
                ->required(),
        ])
        ->columnSpanFull()
        ->defaultItems(1),
    ]);
}



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
