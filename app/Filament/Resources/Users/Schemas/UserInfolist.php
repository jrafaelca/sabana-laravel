<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Users\Schemas\Components\UserCreatedAtEntry;
use App\Filament\Resources\Users\Schemas\Components\UserEmailEntry;
use App\Filament\Resources\Users\Schemas\Components\UserEmailVerifiedAtEntry;
use App\Filament\Resources\Users\Schemas\Components\UserNameEntry;
use App\Filament\Resources\Users\Schemas\Components\UserUpdatedAtEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                UserNameEntry::make(),
                UserEmailEntry::make(),
                UserEmailVerifiedAtEntry::make(),
                UserCreatedAtEntry::make(),
                UserUpdatedAtEntry::make(),
            ]);
    }
}
