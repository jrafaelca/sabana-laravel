<?php

namespace Tests\Unit\Actions;

use App\Actions\CreateOrderAction;
use App\Enums\OrderStatus;
use App\Models\User;
use Tests\TestCase;

class CreateOrderActionTest extends TestCase
{
    public function test_it_creates_an_order_and_assigns_the_authenticated_creator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = CreateOrderAction::handle([
            'status' => OrderStatus::Pending,
            'notes' => 'Orden de prueba',
            'total' => 15.75,
        ]);

        $this->assertSame($user->id, $order->creator_id);
        $this->assertSame(15.75, (float) $order->total);
        $this->assertSame(OrderStatus::Pending, $order->status);
    }
}
