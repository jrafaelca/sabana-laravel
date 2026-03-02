<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(trans('filament/resources/user.infolist.name.label'))
                    ->placeholder(trans('filament/resources/user.infolist.name.placeholder'))
                    ->helperText(trans('filament/resources/user.infolist.name.helper_text'))
                    ->hint(trans('filament/resources/user.infolist.name.hint')),
                TextEntry::make('email')
                    ->label(trans('filament/resources/user.infolist.email.label'))
                    ->placeholder(trans('filament/resources/user.infolist.email.placeholder'))
                    ->helperText(trans('filament/resources/user.infolist.email.helper_text'))
                    ->hint(trans('filament/resources/user.infolist.email.hint')),
                TextEntry::make('email_verified_at')
                    ->label(trans('filament/resources/user.infolist.email_verified_at.label'))
                    ->placeholder(trans('filament/resources/user.infolist.email_verified_at.placeholder'))
                    ->helperText(trans('filament/resources/user.infolist.email_verified_at.helper_text'))
                    ->hint(trans('filament/resources/user.infolist.email_verified_at.hint'))
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->label(trans('filament/resources/user.infolist.created_at.label'))
                    ->placeholder(trans('filament/resources/user.infolist.created_at.placeholder'))
                    ->helperText(trans('filament/resources/user.infolist.created_at.helper_text'))
                    ->hint(trans('filament/resources/user.infolist.created_at.hint'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(trans('filament/resources/user.infolist.updated_at.label'))
                    ->placeholder(trans('filament/resources/user.infolist.updated_at.placeholder'))
                    ->helperText(trans('filament/resources/user.infolist.updated_at.helper_text'))
                    ->hint(trans('filament/resources/user.infolist.updated_at.hint'))
                    ->dateTime(),
            ]);
    }
}
