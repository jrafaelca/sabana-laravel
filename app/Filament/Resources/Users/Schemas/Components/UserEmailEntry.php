<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class UserEmailEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('email')
            ->label(trans('filament/resources/user.infolist.email.label'))
            ->placeholder(trans('filament/resources/user.infolist.email.placeholder'))
            ->helperText(trans('filament/resources/user.infolist.email.helper_text'))
            ->hint(trans('filament/resources/user.infolist.email.hint'));
    }
}
