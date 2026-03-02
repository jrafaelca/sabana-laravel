<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Repeater::make('orderProducts')
                    ->label(trans('filament/resources/order.form.fields.order_products.label'))
                    ->relationship()
                    ->table([
                        TableColumn::make(trans('filament/resources/order.table.columns.product')),
                        TableColumn::make(trans('filament/resources/order.table.columns.quantity')),
                        TableColumn::make(trans('filament/resources/order.table.columns.price')),
                        TableColumn::make(trans('filament/resources/order.table.columns.total')),
                    ])
                    ->schema([
                        Select::make('product_id')
                            ->label(trans('filament/resources/order.form.fields.product.label'))
                            ->placeholder(trans('filament/resources/order.form.fields.product.placeholder'))
                            ->helperText(trans('filament/resources/order.form.fields.product.helper_text'))
                            ->hint(trans('filament/resources/order.form.fields.product.hint'))
                            ->relationship(
                                name: 'product',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('name'),
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                                if (! $state) {
                                    $set('unit_price', null);
                                    $set('total_price', null);

                                    return;
                                }

                                $price = Product::query()
                                    ->whereKey($state)
                                    ->value('price');

                                $quantity = (int) ($get('quantity') ?? 1);

                                $set('unit_price', $price);
                                $set('total_price', $price * $quantity);
                            })
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        TextInput::make('quantity')
                            ->label(trans('filament/resources/order.form.fields.quantity.label'))
                            ->placeholder(trans('filament/resources/order.form.fields.quantity.placeholder'))
                            ->helperText(trans('filament/resources/order.form.fields.quantity.helper_text'))
                            ->hint(trans('filament/resources/order.form.fields.quantity.hint'))
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get): void {
                                $quantity = max(1, (int) ($state ?? 1));
                                $unitPrice = (float) ($get('unit_price') ?? 0);

                                $set('total_price', $unitPrice * $quantity);
                            })
                            ->required(),
                        TextInput::make('unit_price')
                            ->label(trans('filament/resources/order.form.fields.unit_price.label'))
                            ->placeholder(trans('filament/resources/order.form.fields.unit_price.placeholder'))
                            ->helperText(trans('filament/resources/order.form.fields.unit_price.helper_text'))
                            ->hint(trans('filament/resources/order.form.fields.unit_price.hint'))
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('total_price')
                            ->label(trans('filament/resources/order.form.fields.total_price.label'))
                            ->placeholder(trans('filament/resources/order.form.fields.total_price.placeholder'))
                            ->helperText(trans('filament/resources/order.form.fields.total_price.helper_text'))
                            ->hint(trans('filament/resources/order.form.fields.total_price.hint'))
                            ->numeric()
                            ->disabled()
                            ->default(0)
                            ->dehydrated(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])
                    ->columnSpanFull()
                    ->schema([
                        Section::make()
                            ->schema([
                                Textarea::make('notes')
                                    ->label(trans('filament/resources/order.form.fields.notes.label'))
                                    ->placeholder(trans('filament/resources/order.form.fields.notes.placeholder'))
                                    ->helperText(trans('filament/resources/order.form.fields.notes.helper_text'))
                                    ->hint(trans('filament/resources/order.form.fields.notes.hint'))
                                    ->minLength(2)
                                    ->maxLength(190),
                            ]),
                        Section::make(trans('filament/resources/order.form.sections.grand_total.label'))
                            ->schema([
                                Placeholder::make('grand_total')
                                    ->label(trans('filament/resources/order.form.fields.grand_total.label'))
                                    ->content(function (Get $get): string {
                                        $orderProducts = $get('orderProducts');

                                        if (! is_array($orderProducts)) {
                                            return '$0.00';
                                        }

                                        $grandTotal = collect($orderProducts)
                                            ->sum(fn (mixed $orderProduct): float => (float) data_get($orderProduct, 'total_price', 0));

                                        return '$'.number_format($grandTotal, 2, '.', ',');
                                    }),
                            ]),
                    ]),
            ]);
    }
}
