<?php

namespace Tests\Feature\Filament;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\Payment;
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

    public function test_it_processes_pending_order_and_redirects_to_view_page(): void
    {
        $creator = User::factory()->create();
        $server = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa C',
            'total' => 18.00,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        Livewire::test(ListOrders::class)
            ->callTableAction('processOrder', $order)
            ->assertRedirect(OrderResource::getUrl('view', ['record' => $order]));

        $order->refresh();

        $this->assertSame(OrderStatus::InProgress, $order->status);
    }

    public function test_it_processes_pending_order_from_view_page_header_action(): void
    {
        $creator = User::factory()->create();
        $server = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa D',
            'total' => 22.00,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        Livewire::test(ViewOrder::class, ['record' => $order->getKey()])
            ->callAction('processOrder')
            ->assertNotified();

        $order->refresh();

        $this->assertSame(OrderStatus::InProgress, $order->status);
    }

    public function test_it_completes_in_progress_order_from_table_action(): void
    {
        $creator = User::factory()->create();
        $originalServer = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::InProgress,
            'notes' => 'Mesa E',
            'total' => 25.00,
            'creator_id' => $creator->id,
            'server_id' => $originalServer->id,
        ]);

        Payment::query()->create([
            'reference' => 'PAY-TABLE-001',
            'method' => PaymentMethods::Cash,
            'amount' => 25.00,
            'note' => 'Pago total',
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);

        Livewire::test(ListOrders::class)
            ->callTableAction('completeOrder', $order)
            ->assertNotified();

        $order->refresh();

        $this->assertSame(OrderStatus::Completed, $order->status);
        $this->assertNotNull($order->completed_at);
        $this->assertSame($creator->id, $order->server_id);
    }

    public function test_it_does_not_allow_editing_a_completed_order(): void
    {
        $creator = User::factory()->create();
        $this->actingAs($creator);

        $order = Order::query()->create([
            'status' => OrderStatus::Completed,
            'notes' => 'Mesa F',
            'total' => 30.00,
            'creator_id' => $creator->id,
            'server_id' => $creator->id,
            'completed_at' => now(),
        ]);

        Livewire::test(EditOrder::class, ['record' => $order->getKey()])
            ->assertForbidden();
    }
}
