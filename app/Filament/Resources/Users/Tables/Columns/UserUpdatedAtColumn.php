<?php

namespace App\Filament\Resources\Users\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class UserUpdatedAtColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(trans('filament/resources/user.table.columns.updated_at'))
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
