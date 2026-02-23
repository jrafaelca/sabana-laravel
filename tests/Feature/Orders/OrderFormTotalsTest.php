<?php

namespace Tests\Feature\Orders;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class OrderFormTotalsTest extends TestCase
{
    public function test_it_creates_an_order_with_persisted_total_and_item_totals(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $burger = $this->createProduct(name: 'Hamburguesa', price: 10.00);
        $fries = $this->createProduct(name: 'Papas', price: 5.50);
        $firstItemKey = Str::uuid()->toString();
        $secondItemKey = Str::uuid()->toString();

        Livewire::test(CreateOrder::class)
            ->set('data.status', OrderStatus::Pending->value)
            ->set('data.notes', 'Mesa 7')
            ->set('data.orderProducts', [
                $firstItemKey => [
                    'product_id' => $burger->id,
                    'quantity' => 3,
                    'unit_price' => $burger->price,
                    'total_price' => $burger->price * 3,
                ],
                $secondItemKey => [
                    'product_id' => $fries->id,
                    'quantity' => 2,
                    'unit_price' => $fries->price,
                    'total_price' => $fries->price * 2,
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified()
            ->assertRedirect();

        $order = Order::query()->latest('id')->firstOrFail();

        $this->assertSame(41.0, (float) $order->total);
        $this->assertSame(2, $order->orderProducts()->count());
        $this->assertSame(
            41.0,
            (float) $order->orderProducts()->sum('total_price')
        );
    }

    public function test_it_updates_an_existing_order_and_recalculates_persisted_total(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $pizza = $this->createProduct(name: 'Pizza', price: 12.50);
        $drink = $this->createProduct(name: 'Refresco', price: 3.25);
        $firstItemKey = Str::uuid()->toString();
        $secondItemKey = Str::uuid()->toString();

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'creator_id' => $user->id,
            'server_id' => $user->id,
            'notes' => 'Mesa 4',
            'total' => 28.25,
        ]);

        OrderProduct::query()->create([
            'order_id' => $order->id,
            'product_id' => $pizza->id,
            'sort' => 0,
            'quantity' => 2,
            'description' => $pizza->name,
            'cost' => $pizza->cost,
            'unit_price' => $pizza->price,
            'total_price' => 25.00,
        ]);

        OrderProduct::query()->create([
            'order_id' => $order->id,
            'product_id' => $drink->id,
            'sort' => 1,
            'quantity' => 1,
            'description' => $drink->name,
            'cost' => $drink->cost,
            'unit_price' => $drink->price,
            'total_price' => 3.25,
        ]);

        Livewire::test(EditOrder::class, ['record' => $order->getKey()])
            ->set('data.status', OrderStatus::InProgress->value)
            ->set('data.notes', 'Mesa 4 actualizada')
            ->set('data.orderProducts', [
                $firstItemKey => [
                    'product_id' => $pizza->id,
                    'quantity' => 2,
                    'unit_price' => $pizza->price,
                    'total_price' => $pizza->price * 2,
                ],
                $secondItemKey => [
                    'product_id' => $drink->id,
                    'quantity' => 3,
                    'unit_price' => $drink->price,
                    'total_price' => $drink->price * 3,
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $order->refresh();

        $this->assertSame(34.75, (float) $order->total);
        $this->assertSame(
            34.75,
            (float) $order->orderProducts()->sum('total_price')
        );
    }

    public function test_it_recalculates_item_totals_when_quantity_changes_in_form(): void
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
            ->assertSet('data.orderProducts.1.total_price', 11.0);
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
