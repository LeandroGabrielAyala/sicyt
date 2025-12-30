<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use BezhanSalleh\FilamentShield\Resources\RoleResource as ShieldRoleResource;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;



class RoleResource extends ShieldRoleResource
{
    protected static ?string $navigationLabel = 'Roles';
    protected static ?string $modelLabel = 'Roles';
    protected static ?string $slug = 'roles';
    protected static ?string $navigationGroup = 'Personal';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? static::$cluster;
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.role.label');
    }

    public static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.role.icon');
    }

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        return Utils::getSubNavigationPosition() ?? static::$subNavigationPosition;
    }

    public static function getSlug(): string
    {
        return Utils::getResourceSlug();
    }

    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable()
            && count(static::getGloballySearchableAttributes())
            && static::canViewAny();
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->label(__('filament-shield::filament-shield.field.name'))
                        ->required()
                        ->maxLength(255)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) =>
                                Utils::isTenancyEnabled()
                                    ? $rule->where(
                                        Utils::getTenantModelForeignKey(),
                                        Filament::getTenant()?->id
                                    )
                                    : $rule
                        ),

                    TextInput::make('guard_name')
                        ->label(__('filament-shield::filament-shield.field.guard_name'))
                        ->default(Utils::getFilamentAuthGuard())
                        ->nullable()
                        ->maxLength(255),

                    Select::make(config('permission.column_names.team_foreign_key'))
                        ->label(__('filament-shield::filament-shield.field.team'))
                        ->placeholder(__('filament-shield::filament-shield.field.team.placeholder'))
                        ->default([Filament::getTenant()?->id])
                        ->options(fn (): Arrayable =>
                            Utils::getTenantModel()
                                ? Utils::getTenantModel()::pluck('name', 'id')
                                : collect()
                        )
                        ->hidden(fn (): bool =>
                            !(static::shield()->isCentralApp() && Utils::isTenancyEnabled())
                        )
                        ->dehydrated(fn (): bool =>
                            !(static::shield()->isCentralApp() && Utils::isTenancyEnabled())
                        ),

                    ShieldSelectAllToggle::make('select_all')
                        ->onIcon('heroicon-s-shield-check')
                        ->offIcon('heroicon-s-shield-exclamation')
                        ->label(__('filament-shield::filament-shield.field.select_all.name'))
                        ->helperText(fn (): HtmlString =>
                            new HtmlString(__('filament-shield::filament-shield.field.select_all.message'))
                        )
                        ->dehydrated(fn (bool $state): bool => $state),
                ])->columns([
                    'sm' => 2,
                    'lg' => 3,
                ]),
            ]),

            // 👇 ESTO ES CORRECTO EN SHIELD v3
            static::getShieldFormComponents(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament-shield::filament-shield.column.name'))
                    ->formatStateUsing(fn ($state) => Str::headline($state))
                    ->searchable(),

                TextColumn::make('guard_name')
                    ->badge()
                    ->color('warning')
                    ->label(__('filament-shield::filament-shield.column.guard_name')),

                TextColumn::make('team.name')
                    ->default('Global')
                    ->badge()
                    ->color(fn ($state) => str($state)->contains('Global') ? 'gray' : 'primary')
                    ->label(__('filament-shield::filament-shield.column.team'))
                    ->visible(fn () =>
                        static::shield()->isCentralApp() && Utils::isTenancyEnabled()
                    ),

                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->badge()
                    ->colors(['success'])
                    ->label(__('filament-shield::filament-shield.column.permissions')),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('filament-shield::filament-shield.column.updated_at')),
            ])
            ->actions([
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Borrar'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}