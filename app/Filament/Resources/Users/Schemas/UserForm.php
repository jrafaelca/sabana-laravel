<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label(trans('filament/resources/user.form.fields.name.label'))
                    ->placeholder(trans('filament/resources/user.form.fields.name.placeholder'))
                    ->helperText(trans('filament/resources/user.form.fields.name.helper_text'))
                    ->hint(trans('filament/resources/user.form.fields.name.hint'))
                    ->required()
                    ->minLength(2)
                    ->maxLength(190),
                TextInput::make('email')
                    ->label(trans('filament/resources/user.form.fields.email.label'))
                    ->placeholder(trans('filament/resources/user.form.fields.email.placeholder'))
                    ->helperText(trans('filament/resources/user.form.fields.email.helper_text'))
                    ->hint(trans('filament/resources/user.form.fields.email.hint'))
                    ->required()
                    ->email()
                    ->maxLength(190)
                    ->unique(User::class, 'email', ignoreRecord: true),
                TextInput::make('password')
                    ->label(trans('filament/resources/user.form.fields.password.label'))
                    ->placeholder(trans('filament/resources/user.form.fields.password.placeholder'))
                    ->helperText(trans('filament/resources/user.form.fields.password.helper_text'))
                    ->hint(trans('filament/resources/user.form.fields.password.hint'))
                    ->password()
                    ->revealable()
                    ->autocomplete('new-password')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->minLength(8),
            ]);
    }
}
