<?php

namespace App\Filament\Resources\Users\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class UserCreatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('created_at')
            ->label(trans('filament/resources/user.table.columns.created_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
