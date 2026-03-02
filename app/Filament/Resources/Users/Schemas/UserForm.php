<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Users\Schemas\Components\UserEmailInput;
use App\Filament\Resources\Users\Schemas\Components\UserNameInput;
use App\Filament\Resources\Users\Schemas\Components\UserPasswordInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                UserNameInput::make(),
                UserEmailInput::make(),
                UserPasswordInput::make(),
            ]);
    }
}
