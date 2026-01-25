<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Resources\Orders\Schemas\Components\OrderNotesTextarea;
use App\Filament\Resources\Orders\Schemas\Components\OrderProductRepeater;
use App\Filament\Resources\Orders\Schemas\Components\OrderStatusSelect;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(3)
                    ->columnSpanFull()
                    ->schema([
                        OrderStatusSelect::make(),
                    ]),
                OrderProductRepeater::make(),
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        OrderNotesTextarea::make(),
                    ]),
            ]);
    }
}
