<?php

namespace Tests\Unit\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Services\Dashboard\OrderMetricsService;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class OrderMetricsServiceTest extends TestCase
{
    public function test_it_calculates_monthly_kpis_with_previous_period_comparison(): void
    {
        $now = CarbonImmutable::parse('2026-02-23 12:00:00', 'UTC');

        $creator = User::factory()->create();
        $server = User::factory()->create();

        $productA = $this->createProduct('Burger', 'burger', 30, 50);
        $productB = $this->createProduct('Pasta', 'pasta', 20, 50);

        $orderCurrentA = $this->createCompletedOrder($creator, $server, '2026-02-10 10:00:00', 100);
        $this->attachOrderProduct($orderCurrentA, $productA, 2, 30, 50, 100);

        $orderCurrentB = $this->createCompletedOrder($creator, $server, '2026-02-20 20:00:00', 50);
        $this->attachOrderProduct($orderCurrentB, $productB, 1, 20, 50, 50);

        $orderPrevious = $this->createCompletedOrder($creator, $server, '2026-01-12 12:00:00', 120);
        $this->attachOrderProduct($orderPrevious, $productA, 3, 20, 40, 120);

        $this->createPendingOrder($creator, $server, '2026-02-15 12:00:00', 999);

        $this->createPayment($orderCurrentA, $creator, PaymentMethods::Cash, 80);
        $this->createPayment($orderCurrentB, $creator, PaymentMethods::DebitCard, 20);
        $this->createPayment($orderPrevious, $creator, PaymentMethods::Cash, 60);

        $service = app(OrderMetricsService::class);
        $result = $service->getKpis(OrderMetricsService::PERIOD_MONTH, $now);

        $this->assertEqualsWithDelta(150.0, $result['metrics']['sales']['current'], 0.001);
        $this->assertEqualsWithDelta(120.0, $result['metrics']['sales']['previous'], 0.001);
        $this->assertEqualsWithDelta(25.0, (float) $result['metrics']['sales']['delta_percentage'], 0.001);

        $this->assertEqualsWithDelta(3.0, $result['metrics']['products_sold']['current'], 0.001);
        $this->assertEqualsWithDelta(3.0, $result['metrics']['products_sold']['previous'], 0.001);

        $this->assertEqualsWithDelta(2.0, $result['metrics']['completed_orders']['current'], 0.001);
        $this->assertEqualsWithDelta(1.0, $result['metrics']['completed_orders']['previous'], 0.001);

        $this->assertEqualsWithDelta(75.0, $result['metrics']['average_ticket']['current'], 0.001);
        $this->assertEqualsWithDelta(120.0, $result['metrics']['average_ticket']['previous'], 0.001);

        $this->assertEqualsWithDelta(70.0, $result['metrics']['gross_profit']['current'], 0.001);
        $this->assertEqualsWithDelta(30.0, $result['metrics']['gross_profit']['previous'], 0.001);

        $this->assertEqualsWithDelta(46.6666, $result['metrics']['gross_margin_percentage']['current'], 0.01);
        $this->assertEqualsWithDelta(25.0, $result['metrics']['gross_margin_percentage']['previous'], 0.01);

        $this->assertEqualsWithDelta(100.0, $result['metrics']['collected']['current'], 0.001);
        $this->assertEqualsWithDelta(60.0, $result['metrics']['collected']['previous'], 0.001);

        $this->assertEqualsWithDelta(66.6666, $result['metrics']['collection_rate_percentage']['current'], 0.01);
        $this->assertEqualsWithDelta(50.0, $result['metrics']['collection_rate_percentage']['previous'], 0.01);
    }

    public function test_it_returns_null_delta_percentage_when_previous_period_is_zero(): void
    {
        $now = CarbonImmutable::parse('2026-02-23 12:00:00', 'UTC');

        $creator = User::factory()->create();
        $server = User::factory()->create();
        $product = $this->createProduct('Soda', 'soda', 1, 3);

        $order = $this->createCompletedOrder($creator, $server, '2026-02-23 10:00:00', 30);
        $this->attachOrderProduct($order, $product, 10, 1, 3, 30);

        $service = app(OrderMetricsService::class);
        $result = $service->getKpis(OrderMetricsService::PERIOD_DAY, $now);

        $this->assertSame(30.0, $result['metrics']['sales']['current']);
        $this->assertSame(0.0, $result['metrics']['sales']['previous']);
        $this->assertNull($result['metrics']['sales']['delta_percentage']);
    }

    public function test_it_builds_trend_data_with_aligned_series(): void
    {
        $now = CarbonImmutable::parse('2026-02-23 12:00:00', 'UTC');

        $creator = User::factory()->create();
        $server = User::factory()->create();
        $product = $this->createProduct('Pizza', 'pizza', 8, 20);

        $todayOrder = $this->createCompletedOrder($creator, $server, '2026-02-23 09:00:00', 20);
        $this->attachOrderProduct($todayOrder, $product, 1, 8, 20, 20);

        $yesterdayOrder = $this->createCompletedOrder($creator, $server, '2026-02-22 12:00:00', 10);
        $this->attachOrderProduct($yesterdayOrder, $product, 1, 8, 10, 10);

        $service = app(OrderMetricsService::class);
        $trend = $service->getTrendData(OrderMetricsService::PERIOD_DAY, 'sales', $now);

        $this->assertCount(1, $trend['labels']);
        $this->assertEquals([20.0], $trend['current']);
        $this->assertEquals([10.0], $trend['previous']);
    }

    public function test_it_builds_payment_methods_breakdown_for_selected_period(): void
    {
        $now = CarbonImmutable::parse('2026-02-23 12:00:00', 'UTC');

        $creator = User::factory()->create();
        $server = User::factory()->create();
        $product = $this->createProduct('Ensalada', 'ensalada', 5, 15);

        $order = $this->createCompletedOrder($creator, $server, '2026-02-22 18:00:00', 30);
        $this->attachOrderProduct($order, $product, 2, 5, 15, 30);

        $this->createPayment($order, $creator, PaymentMethods::Cash, 10);
        $this->createPayment($order, $creator, PaymentMethods::Zelle, 20);

        $service = app(OrderMetricsService::class);
        $rows = $service->getPaymentMethodsBreakdown(OrderMetricsService::PERIOD_MONTH, $now);

        $this->assertCount(2, $rows);
        $this->assertEqualsCanonicalizing(
            ['cash', 'zelle'],
            $rows->pluck('method')->map(fn (PaymentMethods $method): string => $method->value)->all(),
        );
        $this->assertEqualsWithDelta(20.0, (float) $rows->firstWhere('method', PaymentMethods::Zelle)->total_collected, 0.001);
    }

    public function test_it_returns_top_products_query_with_previous_variation_fields(): void
    {
        CarbonImmutable::setTestNow('2026-02-23 12:00:00');

        $creator = User::factory()->create();
        $server = User::factory()->create();

        $productA = $this->createProduct('Cafe', 'cafe', 1, 3);
        $productB = $this->createProduct('Torta', 'torta', 2, 6);

        $orderCurrent = $this->createCompletedOrder($creator, $server, '2026-02-20 12:00:00', 15);
        $this->attachOrderProduct($orderCurrent, $productA, 3, 1, 3, 9);
        $this->attachOrderProduct($orderCurrent, $productB, 1, 2, 6, 6);

        $orderPrevious = $this->createCompletedOrder($creator, $server, '2026-01-20 12:00:00', 6);
        $this->attachOrderProduct($orderPrevious, $productB, 1, 2, 6, 6);

        $service = app(OrderMetricsService::class);
        $rows = $service->getTopProductsQuery(OrderMetricsService::PERIOD_MONTH)->get();

        $this->assertCount(2, $rows);
        $this->assertSame('Cafe', $rows->first()->name);
        $this->assertEqualsWithDelta(0.0, (float) $rows->firstWhere('name', 'Cafe')->previous_sales_total, 0.001);
        $this->assertEqualsWithDelta(6.0, (float) $rows->firstWhere('name', 'Torta')->previous_sales_total, 0.001);

        CarbonImmutable::setTestNow();
    }

    public function test_it_returns_payment_method_label_for_enum_instances(): void
    {
        $service = app(OrderMetricsService::class);

        $label = $service->paymentMethodLabel(PaymentMethods::DebitCard);

        $this->assertSame((string) PaymentMethods::DebitCard->getLabel(), $label);
    }

    private function createProduct(string $name, string $slug, float $cost, float $price): Product
    {
        return Product::query()->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $name,
            'cost' => $cost,
            'price' => $price,
            'status' => ProductStatus::Enabled,
        ]);
    }

    private function createCompletedOrder(User $creator, User $server, string $completedAt, float $total): Order
    {
        return Order::query()->create([
            'status' => OrderStatus::Completed,
            'completed_at' => $completedAt,
            'notes' => null,
            'total' => $total,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);
    }

    private function createPendingOrder(User $creator, User $server, string $completedAt, float $total): Order
    {
        return Order::query()->create([
            'status' => OrderStatus::Pending,
            'completed_at' => $completedAt,
            'notes' => null,
            'total' => $total,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);
    }

    private function attachOrderProduct(Order $order, Product $product, int $quantity, float $cost, float $unitPrice, float $totalPrice): OrderProduct
    {
        return OrderProduct::query()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'sort' => 0,
            'quantity' => $quantity,
            'description' => $product->name,
            'cost' => $cost,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ]);
    }

    private function createPayment(Order $order, User $creator, PaymentMethods $method, float $amount): Payment
    {
        return Payment::query()->create([
            'reference' => uniqid('pay-', true),
            'method' => $method,
            'amount' => $amount,
            'note' => null,
            'order_id' => $order->id,
            'creator_id' => $creator->id,
        ]);
    }
}
