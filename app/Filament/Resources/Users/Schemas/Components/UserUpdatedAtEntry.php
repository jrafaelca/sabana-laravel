<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class UserUpdatedAtEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('updated_at')
            ->label(trans('filament/resources/user.infolist.updated_at.label'))
            ->placeholder(trans('filament/resources/user.infolist.updated_at.placeholder'))
            ->helperText(trans('filament/resources/user.infolist.updated_at.helper_text'))
            ->hint(trans('filament/resources/user.infolist.updated_at.hint'))
            ->dateTime();
    }
}
