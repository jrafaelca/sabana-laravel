<?php

namespace Tests\Feature\Filament;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Filament\Widgets\SalesKpiOverviewWidget;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWidgetsTest extends TestCase
{
    public function test_sales_kpi_widget_uses_month_as_default_period_when_page_filters_are_missing(): void
    {
        CarbonImmutable::setTestNow('2026-02-23 12:00:00');

        $creator = User::factory()->create();
        $server = User::factory()->create();
        $this->actingAs($creator);

        $product = Product::query()->create([
            'name' => 'Arepa',
            'slug' => 'arepa',
            'description' => 'Arepa',
            'cost' => 2,
            'price' => 5,
            'status' => ProductStatus::Enabled,
        ]);

        $monthOrder = Order::query()->create([
            'status' => OrderStatus::Completed,
            'completed_at' => '2026-02-10 12:00:00',
            'notes' => null,
            'total' => 60,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        $weekOrder = Order::query()->create([
            'status' => OrderStatus::Completed,
            'completed_at' => '2026-02-23 09:00:00',
            'notes' => null,
            'total' => 40,
            'creator_id' => $creator->id,
            'server_id' => $server->id,
        ]);

        OrderProduct::query()->create([
            'order_id' => $monthOrder->id,
            'product_id' => $product->id,
            'sort' => 0,
            'quantity' => 12,
            'description' => $product->name,
            'cost' => 2,
            'unit_price' => 5,
            'total_price' => 60,
        ]);

        OrderProduct::query()->create([
            'order_id' => $weekOrder->id,
            'product_id' => $product->id,
            'sort' => 0,
            'quantity' => 8,
            'description' => $product->name,
            'cost' => 2,
            'unit_price' => 5,
            'total_price' => 40,
        ]);

        Livewire::test(SalesKpiOverviewWidget::class)
            ->assertSee('Ventas cerradas')
            ->assertSee('$100.00', false);

        Livewire::test(SalesKpiOverviewWidget::class, [
            'pageFilters' => ['period' => 'week'],
        ])->assertSee('$40.00', false);

        CarbonImmutable::setTestNow();
    }
}
