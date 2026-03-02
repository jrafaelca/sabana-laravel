<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class UserEmailVerifiedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('email_verified_at')
            ->label(trans('filament/resources/user.infolist.email_verified_at.label'))
            ->placeholder(trans('filament/resources/user.infolist.email_verified_at.placeholder'))
            ->helperText(trans('filament/resources/user.infolist.email_verified_at.helper_text'))
            ->hint(trans('filament/resources/user.infolist.email_verified_at.hint'))
            ->dateTime();
    }
}
