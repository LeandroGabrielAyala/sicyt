<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConvocatoriaBecaResource\Pages;
use App\Filament\Resources\ConvocatoriaBecaResource\RelationManagers;
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

class ConvocatoriaBecaResource extends Resource
{
    protected static ?string $model = ConvocatoriaBeca::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Convocatorias de Becas';
    protected static ?string $modelLabel = 'Convocatorias de Becas';
    protected static ?string $navigationGroup = 'Configuración Becas';
    protected static ?string $slug = 'convocatoria-beca';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo_beca_id')
                    ->relationship('tipo_beca', 'nombre')
                        ->options(TipoBeca::orderBy('nombre', 'desc')->pluck('nombre', 'id')
                    )
                    ->required()
                    ->columnSpanFull(),
                Select::make('anio')
                    ->label('Año de convocatoria')
                    ->options(
                        collect(range(now()->year, now()->year - 50))->mapWithKeys(fn ($year) => [$year => $year])
                    )
                    ->required()
                    ->formatStateUsing(fn ($state) => $state . '-01-01'),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('anio')
                    ->label('Año de Convocatoria')
                    ->sortable(),
                TextColumn::make('tipo_beca.nombre')
                    ->label('Nombre')
                    ->searchable(),
                TextColumn::make('inicio')
                    ->date()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fin')
                    ->date()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean(),
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
            'index' => Pages\ListConvocatoriaBecas::route('/'),
            'create' => Pages\CreateConvocatoriaBeca::route('/create'),
            'edit' => Pages\EditConvocatoriaBeca::route('/{record}/edit'),
        ];
    }
}
