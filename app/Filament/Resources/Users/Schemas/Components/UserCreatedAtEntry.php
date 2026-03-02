<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class UserCreatedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('created_at')
            ->label(trans('filament/resources/user.infolist.created_at.label'))
            ->placeholder(trans('filament/resources/user.infolist.created_at.placeholder'))
            ->helperText(trans('filament/resources/user.infolist.created_at.helper_text'))
            ->hint(trans('filament/resources/user.infolist.created_at.hint'))
            ->dateTime();
    }
}
