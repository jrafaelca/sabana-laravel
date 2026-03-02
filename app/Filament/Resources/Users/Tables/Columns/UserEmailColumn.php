<?php

namespace App\Filament\Resources\Users\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class UserEmailColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('email')
            ->label(trans('filament/resources/user.table.columns.email'))
            ->searchable()
            ->sortable()
            ->toggleable();
    }
}
