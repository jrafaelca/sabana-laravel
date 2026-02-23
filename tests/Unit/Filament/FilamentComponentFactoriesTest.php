<?php

namespace Tests\Unit\Filament;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Schemas\Components\OrderGrandTotalPlaceholder;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemProductSelect;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemQuantityInput;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemsRepeater;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemTotalPriceInput;
use App\Filament\Resources\Orders\Schemas\Components\OrderItemUnitPriceInput;
use App\Filament\Resources\Orders\Schemas\Components\OrderNotesTextarea;
use App\Filament\Resources\Orders\Schemas\Components\OrderStatusSelect;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\Columns\Filters\OrderStatusFilter;
use App\Filament\Resources\Orders\Tables\Columns\OrderCompletedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderCreatedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderCreatorColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderDeletedAtColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderNumberColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderServerColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderStatusColumn;
use App\Filament\Resources\Orders\Tables\Columns\OrderUpdatedAtColumn;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Schemas\Components\ProductCostEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductCostInput;
use App\Filament\Resources\Products\Schemas\Components\ProductCreatedAtEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductDeletedAtEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductDescriptionEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductDescriptionTextarea;
use App\Filament\Resources\Products\Schemas\Components\ProductNameEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductNameInput;
use App\Filament\Resources\Products\Schemas\Components\ProductPriceEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductPriceInput;
use App\Filament\Resources\Products\Schemas\Components\ProductSlugEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductSlugInput;
use App\Filament\Resources\Products\Schemas\Components\ProductStatusEntry;
use App\Filament\Resources\Products\Schemas\Components\ProductStatusSelect;
use App\Filament\Resources\Products\Schemas\Components\ProductUpdatedAtEntry;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Resources\Products\Tables\Columns\ProductCostColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductCreatedAtColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductDeletedAtColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductDescriptionColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductNameColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductPriceColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductSlugColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductStatusColumn;
use App\Filament\Resources\Products\Tables\Columns\ProductUpdatedAtColumn;
use App\Filament\Resources\Products\Tables\Filters\ProductStatusFilter;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use ReflectionClass;
use Tests\TestCase;

class FilamentComponentFactoriesTest extends TestCase
{
    public function test_order_schema_component_factories_return_expected_instances(): void
    {
        $this->assertInstanceOf(Select::class, OrderStatusSelect::make());
        $this->assertInstanceOf(Repeater::class, OrderItemsRepeater::make());
        $this->assertInstanceOf(Select::class, OrderItemProductSelect::make());
        $this->assertInstanceOf(TextInput::class, OrderItemQuantityInput::make());
        $this->assertInstanceOf(TextInput::class, OrderItemUnitPriceInput::make());
        $this->assertInstanceOf(TextInput::class, OrderItemTotalPriceInput::make());
        $this->assertInstanceOf(Textarea::class, OrderNotesTextarea::make());
        $this->assertInstanceOf(Placeholder::class, OrderGrandTotalPlaceholder::make());

        $this->assertSame('status', OrderStatusSelect::make()->getName());
        $this->assertSame('orderProducts', OrderItemsRepeater::make()->getName());
        $this->assertSame('product_id', OrderItemProductSelect::make()->getName());
        $this->assertSame('quantity', OrderItemQuantityInput::make()->getName());
        $this->assertSame('unit_price', OrderItemUnitPriceInput::make()->getName());
        $this->assertSame('total_price', OrderItemTotalPriceInput::make()->getName());
        $this->assertSame('notes', OrderNotesTextarea::make()->getName());
        $this->assertSame('grand_total', OrderGrandTotalPlaceholder::make()->getName());
    }

    public function test_order_table_columns_and_filter_factories_return_expected_instances(): void
    {
        $this->assertInstanceOf(TextColumn::class, OrderNumberColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderStatusColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderCreatorColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderServerColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderCompletedAtColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderCreatedAtColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderUpdatedAtColumn::make());
        $this->assertInstanceOf(TextColumn::class, OrderDeletedAtColumn::make());
        $this->assertInstanceOf(SelectFilter::class, OrderStatusFilter::make());
    }

    public function test_product_schema_component_factories_return_expected_instances(): void
    {
        $this->assertInstanceOf(TextInput::class, ProductNameInput::make());
        $this->assertInstanceOf(TextInput::class, ProductSlugInput::make());
        $this->assertInstanceOf(Textarea::class, ProductDescriptionTextarea::make());
        $this->assertInstanceOf(TextInput::class, ProductCostInput::make());
        $this->assertInstanceOf(TextInput::class, ProductPriceInput::make());
        $this->assertInstanceOf(Select::class, ProductStatusSelect::make());

        $this->assertInstanceOf(TextEntry::class, ProductStatusEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductNameEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductSlugEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductDescriptionEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductCostEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductPriceEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductCreatedAtEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductUpdatedAtEntry::make());
        $this->assertInstanceOf(TextEntry::class, ProductDeletedAtEntry::make());
    }

    public function test_product_table_columns_and_filter_factories_return_expected_instances(): void
    {
        $this->assertInstanceOf(TextColumn::class, ProductStatusColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductNameColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductSlugColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductDescriptionColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductCostColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductPriceColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductCreatedAtColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductUpdatedAtColumn::make());
        $this->assertInstanceOf(TextColumn::class, ProductDeletedAtColumn::make());
        $this->assertInstanceOf(SelectFilter::class, ProductStatusFilter::make());
    }

    public function test_form_and_infolist_schema_configuration_methods_return_schema_instances(): void
    {
        $this->assertInstanceOf(Schema::class, OrderForm::configure(Schema::make()));
        $this->assertInstanceOf(Schema::class, ProductForm::configure(Schema::make()));
        $this->assertInstanceOf(Schema::class, ProductInfolist::configure(Schema::make()));

        $this->assertInstanceOf(Schema::class, OrderResource::form(Schema::make()));
        $this->assertInstanceOf(Schema::class, ProductResource::form(Schema::make()));
        $this->assertInstanceOf(Schema::class, ProductResource::infolist(Schema::make()));
    }

    public function test_resource_and_table_configuration_metadata_methods_return_expected_values(): void
    {
        $orderPages = OrderResource::getPages();
        $productPages = ProductResource::getPages();

        $this->assertArrayHasKey('index', $orderPages);
        $this->assertArrayHasKey('create', $orderPages);
        $this->assertArrayHasKey('edit', $orderPages);
        $this->assertArrayHasKey('index', $productPages);

        $this->assertSame(['id'], OrderResource::getGloballySearchableAttributes());
        $this->assertSame(['name'], ProductResource::getGloballySearchableAttributes());
        $this->assertNotEmpty(OrderResource::getModelLabel());
        $this->assertNotEmpty(OrderResource::getPluralModelLabel());
        $this->assertNotEmpty(OrderResource::getNavigationLabel());
        $this->assertNotEmpty(ProductResource::getModelLabel());
        $this->assertNotEmpty(ProductResource::getPluralModelLabel());
        $this->assertNotEmpty(ProductResource::getNavigationLabel());
        $this->assertNotEmpty(ProductResource::getNavigationGroup());

        $this->assertSame(OrdersTable::class, OrdersTable::class);
        $this->assertSame(ProductsTable::class, ProductsTable::class);
    }

    public function test_order_item_product_select_after_state_updated_callback_computes_prices(): void
    {
        $product = Product::query()->create([
            'name' => 'Ceviche',
            'slug' => 'ceviche',
            'description' => 'Del dia',
            'cost' => 4.00,
            'price' => 8.50,
            'status' => \App\Enums\ProductStatus::Enabled,
        ]);

        $select = OrderItemProductSelect::make();
        $reflection = new ReflectionClass($select);
        $afterStateUpdated = $reflection->getProperty('afterStateUpdated');
        $afterStateUpdated->setAccessible(true);

        /** @var array<int, \Closure> $hooks */
        $hooks = $afterStateUpdated->getValue($select);

        $this->assertNotEmpty($hooks);

        $state = ['quantity' => 3];
        $set = function (string $key, mixed $value) use (&$state): void {
            $state[$key] = $value;
        };
        $get = function (string $key) use (&$state): mixed {
            return $state[$key] ?? null;
        };

        $hooks[0]($product->id, $set, $get);

        $this->assertSame(8.5, (float) $state['unit_price']);
        $this->assertSame(25.5, (float) $state['total_price']);

        $hooks[0](null, $set, $get);

        $this->assertNull($state['unit_price']);
        $this->assertNull($state['total_price']);
    }
}
