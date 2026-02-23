<?php

namespace Tests\Feature\Orders;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderFormTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_recalculates_item_totals_and_grand_total_on_create_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $burger = $this->createProduct(name: 'Hamburguesa', price: 10.00);
        $fries = $this->createProduct(name: 'Papas', price: 5.50);

        Livewire::test(CreateOrder::class)
            ->set('data.orderProducts', [
                [
                    'product_id' => $burger->id,
                    'quantity' => 1,
                    'unit_price' => $burger->price,
                    'total_price' => $burger->price,
                ],
                [
                    'product_id' => $fries->id,
                    'quantity' => 1,
                    'unit_price' => $fries->price,
                    'total_price' => $fries->price,
                ],
            ])
            ->set('data.orderProducts.0.quantity', 3)
            ->set('data.orderProducts.1.quantity', 2)
            ->assertSet('data.orderProducts.0.total_price', 30.0)
            ->assertSet('data.orderProducts.1.total_price', 11.0)
            ->assertSee('$41.00')
            ->set('data.orderProducts', [
                [
                    'product_id' => $burger->id,
                    'quantity' => 3,
                    'unit_price' => $burger->price,
                    'total_price' => 30.0,
                ],
            ])
            ->assertSee('$30.00');
    }

    public function test_it_recalculates_item_totals_and_grand_total_on_edit_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $pizza = $this->createProduct(name: 'Pizza', price: 12.50);
        $drink = $this->createProduct(name: 'Refresco', price: 3.25);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'creator_id' => $user->id,
            'server_id' => $user->id,
            'notes' => 'Mesa 4',
        ]);

        Livewire::test(EditOrder::class, ['record' => $order->getKey()])
            ->set('data.orderProducts', [
                [
                    'product_id' => $pizza->id,
                    'quantity' => 2,
                    'unit_price' => $pizza->price,
                    'total_price' => $pizza->price * 2,
                ],
                [
                    'product_id' => $drink->id,
                    'quantity' => 1,
                    'unit_price' => $drink->price,
                    'total_price' => $drink->price,
                ],
            ])
            ->set('data.orderProducts.1.quantity', 3)
            ->assertSet('data.orderProducts.0.total_price', 25.0)
            ->assertSet('data.orderProducts.1.total_price', 9.75)
            ->assertSee('$34.75');
    }

    private function createProduct(string $name, float $price): Product
    {
        return Product::query()->create([
            'name' => $name,
            'slug' => str($name)->slug().'-'.fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'cost' => $price - 1,
            'price' => $price,
            'status' => ProductStatus::Enabled,
        ]);
    }
}
