<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Resources\Orders\Schemas\Components\OrderGrandTotalPlaceholder;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemsRepeater;
use App\Filament\Resources\Orders\Schemas\Components\OrderNotesTextarea;
use App\Filament\Resources\Orders\Schemas\Components\OrderStatusSelect;
use Filament\Schemas\Components\Grid;
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
                OrderItemsRepeater::make(),
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->schema([
                                OrderNotesTextarea::make(),
                            ]),
                        Section::make(trans('filament/resources/order.form.sections.grand_total.label'))
                            ->schema([
                                OrderGrandTotalPlaceholder::make(),
                            ]),
                    ]),
            ]);
    }
}
