<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use App\Models\User;
use Filament\Forms\Components\TextInput;

class UserEmailInput
{
    public static function make(): TextInput
    {
        return TextInput::make('email')
            ->label(trans('filament/resources/user.form.fields.email.label'))
            ->placeholder(trans('filament/resources/user.form.fields.email.placeholder'))
            ->helperText(trans('filament/resources/user.form.fields.email.helper_text'))
            ->hint(trans('filament/resources/user.form.fields.email.hint'))
            ->required()
            ->email()
            ->maxLength(190)
            ->unique(User::class, 'email', ignoreRecord: true);
    }
}
