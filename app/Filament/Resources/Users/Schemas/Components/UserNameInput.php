<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Forms\Components\TextInput;

class UserNameInput
{
    public static function make(): TextInput
    {
        return TextInput::make('name')
            ->label(trans('filament/resources/user.form.fields.name.label'))
            ->placeholder(trans('filament/resources/user.form.fields.name.placeholder'))
            ->helperText(trans('filament/resources/user.form.fields.name.helper_text'))
            ->hint(trans('filament/resources/user.form.fields.name.hint'))
            ->required()
            ->minLength(2)
            ->maxLength(190);
    }
}
