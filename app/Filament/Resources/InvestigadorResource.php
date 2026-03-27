<?php

namespace App\Filament\Resources;

use App\Models\Investigador;
use App\Models\Disciplina;
use App\Models\User;

use App\Filament\Resources\InvestigadorResource\Pages;
use App\Filament\Resources\InvestigadorResource\RelationManagers\ProyectoRelationManager;
use App\Filament\Resources\InvestigadorResource\RelationManagers\BecariosRelationManager;
use App\Filament\Resources\InvestigadorResource\RelationManagers\AdscriptosRelationManager;

use Filament\Resources\Resource;

use Filament\Forms\Form;
use Filament\Forms\Get;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Section;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\SelectFilter;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;

use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InvestigadorResource extends Resource
{

    protected static ?string $model = Investigador::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Investigadores';

    protected static ?string $modelLabel = 'Investigador';

    protected static ?string $navigationGroup = 'Proyectos';

    protected static ?string $slug = 'investigadores-pi';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'apellido_nombre';


    /*
    |--------------------------------------------------------------------------
    | BADGE
    |--------------------------------------------------------------------------
    */

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    /*
    |--------------------------------------------------------------------------
    | GLOBAL SEARCH
    |--------------------------------------------------------------------------
    */

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->apellido_nombre;
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->with(['cargo']);
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO
    |--------------------------------------------------------------------------
    */

    public static function form(Form $form): Form
    {

        return $form->schema([

            Tabs::make('Formulario Investigador')
            
                ->tabs([

                    /*
                    |--------------------------------------------------------------------------
                    | DATOS PERSONALES
                    |--------------------------------------------------------------------------
                    */

                    Tab::make('Datos Personales')

                        ->schema([

                            Section::make('Información Personal')

                                ->description('Datos personales básicos del investigador.')

                                ->schema([


                                    TextInput::make('nombre')

                                        ->label('Nombre(s)')

                                        ->placeholder('Ej: Juan Carlos')

                                        ->helperText('Ingrese uno o más nombres.')

                                        ->required()

                                        ->maxLength(255),



                                    TextInput::make('apellido')

                                        ->label('Apellido(s)')

                                        ->placeholder('Ej: Pérez Gómez')

                                        ->helperText('Ingrese los apellidos completos.')

                                        ->required()



                                        ->maxLength(255),



                                    DatePicker::make('fecha_nac')

                                        ->label('Fecha de nacimiento')

                                        ->helperText('Seleccione la fecha de nacimiento.')

                                        ->required(),



                                    TextInput::make('lugar_nac')

                                        ->label('Lugar de nacimiento')

                                        ->placeholder('Ej: Resistencia')

                                        ->helperText('Ciudad o localidad de nacimiento.')

                                        ->required(),



                                    TextInput::make('dni')

                                        ->label('DNI')

                                        ->placeholder('Ej: 34567890')

                                        ->mask('99999999')

                                        ->helperText('Ingrese el DNI sin puntos.')

                                        ->required()

                                        ->unique(ignoreRecord: true),



                                    TextInput::make('cuil')

                                        ->label('CUIL')

                                        ->placeholder('Ej: 20-34567890-3')

                                        ->mask('99-99999999-9')

                                        ->helperText('Ingrese el CUIL completo.')

                                        ->required(),



                                    TextInput::make('domicilio')

                                        ->label('Domicilio')

                                        ->placeholder('Ej: Av. Belgrano 123')

                                        ->helperText('Dirección actual.')

                                        ->required(),



                                    TextInput::make('provincia')

                                        ->label('Provincia')

                                        ->placeholder('Ej: Chaco')

                                        ->required(),



                                    TextInput::make('email')

                                        ->label('Correo electrónico')

                                        ->placeholder('ejemplo@email.com')

                                        ->helperText('Se utilizará para crear el usuario del sistema.')

                                        ->email()

                                        ->required()

                                        ->unique(
                                            table: User::class,
                                            column: 'email',
                                            ignoreRecord: true
                                        ),



                                    TextInput::make('telefono')

                                        ->label('Teléfono')

                                        ->placeholder('Ej: 3624123456')

                                        ->helperText('Número sin espacios.')

                                        ->tel()

                                        ->required(),

                                ])

                                ->columns(3),

                        ]),



                    /*
                    |--------------------------------------------------------------------------
                    | CLASIFICACIÓN
                    |--------------------------------------------------------------------------
                    */

                    Tab::make('Clasificación')

                        ->schema([

                            Section::make('Clasificación Académica')

                                ->description('Datos académicos y categorización.')

                                ->schema([


                                    Select::make('nivel_academico_id')

                                        ->label('Nivel Académico')

                                        ->helperText('Seleccione el nivel académico.')

                                        ->relationship('nivelAcademico', 'nombre')

                                        ->required()

                                        ->preload(),



                                    Select::make('objetivo_id')

                                        ->label('Objetivo Socioeconómico')

                                        ->relationship('objetivo', 'nombre')

                                        ->required()

                                        ->preload(),



                                    Select::make('campo_id')

                                        ->label('Campo de Aplicación')

                                        ->relationship('campo', 'nombre')

                                        ->required()

                                        ->live()

                                        ->preload(),



                                    Select::make('disciplina_id')

                                        ->label('Disciplina')

                                        ->helperText('Se carga según el campo seleccionado.')

                                        ->options(

                                            fn(Get $get): Collection =>

                                            Disciplina::query()

                                                ->where(
                                                    'campo_id',
                                                    $get('campo_id')
                                                )

                                                ->pluck('nombre', 'id')

                                        )

                                        ->required()
                                        ->preload(),



                                    Select::make('carrera_id')

                                        ->label('Título del Investigador')

                                        ->relationship('carrera', 'titulo')

                                        ->required()

                                        ->preload(),



                                    TextInput::make('titulo_posgrado')

                                        ->label('Título de posgrado')

                                        ->placeholder('Ej: Doctor en Educación'),



                                    Select::make('cargo_id')

                                        ->label('Cargo docente')

                                        ->relationship('cargo', 'nombre')

                                        ->required()

                                        ->preload(),



                                    Select::make('categoria_interna_id')

                                        ->label('Categoría Interna UNCAUS')

                                        ->relationship('categoriaInterna', 'categoria')

                                        ->required()

                                        ->preload(),



                                    Select::make('incentivo_id')

                                        ->label('Categoría del Incentivo')

                                        ->relationship('incentivo', 'categoria')

                                        ->required()

                                        ->preload(),

                                ])

                                ->columns(3),

                        ]),

                ])

                ->columnSpanFull(),

        ]);

    }



    /*
    |--------------------------------------------------------------------------
    | TABLA
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {

        return $table

            ->columns([

                TextColumn::make('apellido_nombre')

                    ->label('Apellido, Nombre')

                    ->searchable(['apellido', 'nombre'])

                    ->sortable(query: fn ($query, $direction) =>
                        $query->orderBy('apellido', $direction)
                              ->orderBy('nombre', $direction)
                    ),


                TextColumn::make('dni')

                    ->label('DNI')


                    ->searchable(),


                TextColumn::make('email')

                    ->label('Correo electrónico')

                    ->searchable(),


                TextColumn::make('telefono')

                    ->label('Teléfono')

                    ->searchable(),

            ])

            ->defaultSort('apellido', 'asc')



            ->filters([

                SelectFilter::make('campo_id')
                    ->label('Campo de Aplicación')
                    ->relationship('campo', 'nombre'),

                SelectFilter::make('cargo_id')
                    ->label('Cargo docente')
                    ->relationship('cargo', 'nombre'),

            ])



            ->actions([

                ActionGroup::make([

                    ViewAction::make()
                        ->modalHeading(fn ($record) => 'Investigador: ' . $record->apellido_nombre)
                        ->label('Ver')
                        ->color('primary'),


                    EditAction::make()
                        ->modalHeading(fn ($record) => 'Editar datos de: ' . $record->apellido_nombre)
                        ->label('Editar')
                        ->color('primary'),


                    DeleteAction::make()

                        ->label('Eliminar')

                        ->requiresConfirmation()

                        ->modalHeading(fn ($record) => '¿Eliminar investigador/a: ' . $record->apellido_nombre . '?')

                        ->modalSubheading(
                            'Esta acción no se puede deshacer.'
                        )

                        ->modalButton('Sí, eliminar')

                        ->successNotification(

                            Notification::make()

                                ->success()

                                ->title('Investigador eliminado')

                                ->body('El registro fue eliminado correctamente.')

                        ),

                ])

                ->label(''),

            ])



            ->bulkActions([

                BulkActionGroup::make([

                    ExportBulkAction::make()

                        ->label('Exportar seleccionados'),


                    DeleteBulkAction::make()

                        ->label('Eliminar seleccionados')
                        
                        ->requiresConfirmation()

                        ->modalHeading('¿Eliminar todos los seleccionados?')

                        ->modalSubheading(
                            'Esta acción no se puede deshacer.'
                        )

                        ->modalButton('Sí, eliminarlos')

                        ->successNotification(

                            Notification::make()

                                ->success()

                                ->title('Investigadores eliminados')

                                ->body('Los registros fueron eliminados correctamente.')

                        ),

                ])->label('Acciones en lote'),

            ]);

    }



    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {

        return [

            ProyectoRelationManager::class,

            BecariosRelationManager::class,

            AdscriptosRelationManager::class,

        ];

    }



    /*
    |--------------------------------------------------------------------------
    | CREACIÓN AUTOMÁTICA DE USUARIO
    |--------------------------------------------------------------------------
    */

    public static function afterCreate(
        Investigador $record,
        array $data
    ): void {

        $password = Str::random(10);

        $user = User::create([

            'name' => $record->apellido_nombre,

            'email' => $data['email'],

            'password' => bcrypt($password),

        ]);

        $record->update([

            'user_id' => $user->id,

        ]);


        Notification::make()

            ->success()

            ->title('Investigador creado correctamente')

            ->body(
                "Se creó el usuario automáticamente.\n" .
                "Contraseña generada: {$password}"
            )

            ->send();

    }



    /*
    |--------------------------------------------------------------------------
    | PÁGINAS
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {

        return [

            'index' => Pages\ListInvestigadors::route('/'),

            'create' => Pages\CreateInvestigador::route('/create'),

            'edit' => Pages\EditInvestigador::route('/{record}/edit'),

        ];

    }

}