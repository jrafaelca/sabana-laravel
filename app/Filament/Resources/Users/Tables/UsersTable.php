<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\Tables\Columns\UserCreatedAtColumn;
use App\Filament\Resources\Users\Tables\Columns\UserEmailColumn;
use App\Filament\Resources\Users\Tables\Columns\UserEmailVerifiedAtColumn;
use App\Filament\Resources\Users\Tables\Columns\UserNameColumn;
use App\Filament\Resources\Users\Tables\Columns\UserUpdatedAtColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                UserNameColumn::make(),
                UserEmailColumn::make(),
                UserEmailVerifiedAtColumn::make(),
                UserCreatedAtColumn::make(),
                UserUpdatedAtColumn::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->modalWidth('lg'),
                    EditAction::make()->modalWidth('lg'),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
