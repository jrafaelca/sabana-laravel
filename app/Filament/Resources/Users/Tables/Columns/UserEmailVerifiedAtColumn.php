<?php

namespace App\Filament\Resources\Users\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class UserEmailVerifiedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('email_verified_at')
            ->label(trans('filament/resources/user.table.columns.email_verified_at'))
            ->dateTime()
            ->sortable()
            ->toggleable();
    }
}
