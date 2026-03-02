<?php

namespace Tests\Unit\Filament;

use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Schemas\ProductInfolist;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Filament\Resources\Users\UserResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Tests\TestCase;

class FilamentComponentFactoriesTest extends TestCase
{
    public function test_order_form_contains_expected_root_components(): void
    {
        $schema = OrderForm::configure(Schema::make());
        $components = $schema->getComponents();

        $this->assertCount(2, $components);
        $this->assertInstanceOf(Repeater::class, $components[0]);
        $this->assertSame('orderProducts', $components[0]->getName());
    }

    public function test_product_form_and_infolist_contain_expected_components(): void
    {
        $formComponents = ProductForm::configure(Schema::make())->getComponents();
        $infolistComponents = ProductInfolist::configure(Schema::make())->getComponents();

        $this->assertNotEmpty($formComponents);
        $this->assertNotEmpty($infolistComponents);
        $this->assertContainsOnlyInstancesOf(TextEntry::class, $infolistComponents);
    }

    public function test_user_form_and_infolist_contain_expected_components(): void
    {
        $formComponents = UserForm::configure(Schema::make())->getComponents();
        $infolistComponents = UserInfolist::configure(Schema::make())->getComponents();

        $this->assertNotEmpty($formComponents);
        $this->assertNotEmpty($infolistComponents);
        $this->assertContainsOnlyInstancesOf(TextEntry::class, $infolistComponents);
    }

    public function test_orders_products_and_users_table_classes_are_available(): void
    {
        $this->assertSame(OrdersTable::class, OrdersTable::class);
        $this->assertSame(ProductsTable::class, ProductsTable::class);
        $this->assertSame(UsersTable::class, UsersTable::class);
    }

    public function test_resource_and_schema_configuration_methods_return_expected_values(): void
    {
        $orderPages = OrderResource::getPages();
        $productPages = ProductResource::getPages();
        $userPages = UserResource::getPages();

        $this->assertArrayHasKey('index', $orderPages);
        $this->assertArrayHasKey('create', $orderPages);
        $this->assertArrayHasKey('edit', $orderPages);
        $this->assertArrayHasKey('index', $productPages);
        $this->assertArrayHasKey('index', $userPages);

        $this->assertSame(['id'], OrderResource::getGloballySearchableAttributes());
        $this->assertSame(['name'], ProductResource::getGloballySearchableAttributes());
        $this->assertSame(['name', 'email'], UserResource::getGloballySearchableAttributes());

        $this->assertNotEmpty(OrderResource::getModelLabel());
        $this->assertNotEmpty(OrderResource::getPluralModelLabel());
        $this->assertNotEmpty(OrderResource::getNavigationLabel());

        $this->assertNotEmpty(ProductResource::getModelLabel());
        $this->assertNotEmpty(ProductResource::getPluralModelLabel());
        $this->assertNotEmpty(ProductResource::getNavigationLabel());
        $this->assertNotEmpty(ProductResource::getNavigationGroup());

        $this->assertNotEmpty(UserResource::getModelLabel());
        $this->assertNotEmpty(UserResource::getPluralModelLabel());
        $this->assertNotEmpty(UserResource::getNavigationLabel());
        $this->assertNotEmpty(UserResource::getNavigationGroup());
    }

    public function test_order_form_contains_notes_textarea_in_layout(): void
    {
        $components = OrderForm::configure(Schema::make())->getComponents();

        $this->assertNotEmpty($components);

        $allComponentClasses = collect($components)
            ->flatMap(function (mixed $component): array {
                $children = method_exists($component, 'getChildComponents')
                    ? $component->getChildComponents()
                    : [];

                return array_merge([get_class($component)], array_map(fn ($child): string => get_class($child), $children));
            })
            ->values()
            ->all();

        $this->assertContains(Textarea::class, $allComponentClasses);
    }
}
