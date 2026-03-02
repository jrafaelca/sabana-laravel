<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Forms\Components\TextInput;

class UserPasswordInput
{
    public static function make(): TextInput
    {
        return TextInput::make('password')
            ->label(trans('filament/resources/user.form.fields.password.label'))
            ->placeholder(trans('filament/resources/user.form.fields.password.placeholder'))
            ->helperText(trans('filament/resources/user.form.fields.password.helper_text'))
            ->hint(trans('filament/resources/user.form.fields.password.hint'))
            ->password()
            ->revealable()
            ->autocomplete('new-password')
            ->required(fn (string $operation): bool => $operation === 'create')
            ->dehydrated(fn (?string $state): bool => filled($state))
            ->minLength(8);
    }
}
