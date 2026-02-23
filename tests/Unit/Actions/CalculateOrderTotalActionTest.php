<?php

namespace Tests\Unit\Actions;

use App\Actions\CalculateOrderTotalAction;
use PHPUnit\Framework\TestCase;

class CalculateOrderTotalActionTest extends TestCase
{
    public function test_it_sums_order_product_totals(): void
    {
        $total = CalculateOrderTotalAction::handle([
            ['total_price' => 10.50],
            ['total_price' => 5],
            ['total_price' => '2.25'],
        ]);

        $this->assertSame(17.75, $total);
    }

    public function test_it_returns_zero_when_no_items_are_provided(): void
    {
        $total = CalculateOrderTotalAction::handle([]);

        $this->assertSame(0.0, $total);
    }
}
