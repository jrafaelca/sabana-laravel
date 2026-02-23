<?php

namespace Tests\Feature\Filament;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class OrdersListPageTest extends TestCase
{
    public function test_it_renders_orders_table_and_can_search_records(): void
    {
        $creator = User::factory()->create();
        $server = User::factory()->create();
        $this->actingAs($creator);

        $firstOrder = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa A',
            'total' => 12.00,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        $secondOrder = Order::query()->create([
            'status' => OrderStatus::Completed,
            'notes' => 'Mesa B',
            'total' => 20.00,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        $orders = Order::query()->whereKey([$firstOrder->id, $secondOrder->id])->get();

        Livewire::test(ListOrders::class)
            ->assertCanSeeTableRecords($orders)
            ->searchTable((string) $firstOrder->id)
            ->assertCanSeeTableRecords($orders->where('id', $firstOrder->id))
            ->assertCanNotSeeTableRecords($orders->where('id', $secondOrder->id));
    }
}
