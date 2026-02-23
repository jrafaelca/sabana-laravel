<?php

namespace Tests\Unit\Models;

use App\Actions\CreatePaymentAction;
use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Panel;
use Tests\TestCase;

class ModelBehaviorTest extends TestCase
{
    public function test_model_relationships_and_casts_work_as_expected(): void
    {
        $creator = User::factory()->create();
        $server = User::factory()->create();

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => 'Mesa 9',
            'total' => 0,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        $product = Product::query()->create([
            'name' => 'Limonada',
            'slug' => 'limonada',
            'description' => 'Natural',
            'cost' => 1.25,
            'price' => 3.75,
            'status' => ProductStatus::Enabled,
        ]);

        $orderProduct = OrderProduct::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'sort' => 0,
            'quantity' => 2,
            'description' => '',
            'cost' => 0,
            'unit_price' => 3.75,
            'total_price' => 7.50,
        ]);

        $payment = Payment::query()->create([
            'reference' => 'PAY-001',
            'method' => PaymentMethods::Cash,
            'amount' => 7.50,
            'note' => 'Pago completo',
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);

        $this->assertInstanceOf(OrderStatus::class, $order->status);
        $this->assertInstanceOf(ProductStatus::class, $product->status);
        $this->assertInstanceOf(PaymentMethods::class, $payment->method);

        $this->assertTrue($order->creator->is($creator));
        $this->assertTrue($order->server->is($server));
        $this->assertTrue($payment->order->is($order));
        $this->assertTrue($payment->creator->is($creator));
        $this->assertTrue($orderProduct->order->is($order));
        $this->assertTrue($orderProduct->product->is($product));

        $this->assertSame($product->name, $orderProduct->description);
        $this->assertSame((float) $product->cost, (float) $orderProduct->cost);

        $this->assertSame(1, $creator->createdOrders()->count());
        $this->assertSame(1, $server->servedOrders()->count());
        $this->assertSame(1, $order->orderProducts()->count());
        $this->assertSame(1, $order->payments()->count());
    }

    public function test_create_payment_action_assigns_authenticated_creator(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $order = Order::query()->create([
            'status' => OrderStatus::Pending,
            'notes' => null,
            'total' => 0,
            'creator_id' => $user->id,
            'server_id' => $user->id,
        ]);

        $payment = CreatePaymentAction::handle([
            'reference' => 'PAY-002',
            'method' => PaymentMethods::Zelle,
            'amount' => 4.20,
            'note' => 'Parcial',
            'order_id' => $order->id,
        ]);

        $this->assertSame($user->id, $payment->creator_id);
        $this->assertSame($order->id, $payment->order_id);
        $this->assertSame(PaymentMethods::Zelle, $payment->method);
    }

    public function test_user_access_and_admin_panel_provider_configuration_are_accessible(): void
    {
        $verifiedUser = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();
        $panel = Panel::make();

        $this->assertTrue($verifiedUser->canAccessPanel($panel));
        $this->assertFalse($unverifiedUser->canAccessPanel($panel));

        $provider = new AdminPanelProvider(app());
        $configuredPanel = $provider->panel(Panel::make());

        $this->assertInstanceOf(Panel::class, $configuredPanel);
    }
}
