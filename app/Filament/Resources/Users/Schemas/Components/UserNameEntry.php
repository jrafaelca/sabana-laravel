<?php

namespace App\Filament\Resources\Users\Schemas\Components;

use Filament\Infolists\Components\TextEntry;

class UserNameEntry
{
    public static function make(): TextEntry
    {
        return TextEntry::make('name')
            ->label(trans('filament/resources/user.infolist.name.label'))
            ->placeholder(trans('filament/resources/user.infolist.name.placeholder'))
            ->helperText(trans('filament/resources/user.infolist.name.helper_text'))
            ->hint(trans('filament/resources/user.infolist.name.hint'));
    }
}
