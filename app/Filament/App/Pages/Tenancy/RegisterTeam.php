<?php

namespace App\Filament\App\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nombre')->required(),
                TextInput::make('slug')->required(),
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create([
            'nombre' => $data['nombre'],
            'slug' => $data['slug'],
        ]);

        $team->members()->attach(auth()->user());

        return $team;
    }
}