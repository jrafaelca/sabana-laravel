<?php

namespace Tests\Unit\Enums;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethods;
use App\Enums\ProductStatus;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Tests\TestCase;

class EnumContractsTest extends TestCase
{
    public function test_order_status_contract_methods_return_expected_values(): void
    {
        $this->assertSame('pending', OrderStatus::Pending->value);
        $this->assertSame('in_progress', OrderStatus::InProgress->value);
        $this->assertSame('completed', OrderStatus::Completed->value);
        $this->assertSame('cancelled', OrderStatus::Cancelled->value);

        $this->assertSame(trans('order.status.pending'), OrderStatus::Pending->getLabel());
        $this->assertSame(trans('order.status.in_progress'), OrderStatus::InProgress->getLabel());
        $this->assertSame(trans('order.status.completed'), OrderStatus::Completed->getLabel());
        $this->assertSame(trans('order.status.cancelled'), OrderStatus::Cancelled->getLabel());

        $this->assertSame(Color::Amber, OrderStatus::Pending->getColor());
        $this->assertSame(Color::Blue, OrderStatus::InProgress->getColor());
        $this->assertSame(Color::Green, OrderStatus::Completed->getColor());
        $this->assertSame(Color::Red, OrderStatus::Cancelled->getColor());

        $this->assertSame(Heroicon::OutlinedClock, OrderStatus::Pending->getIcon());
        $this->assertSame(Heroicon::OutlinedArrowPath, OrderStatus::InProgress->getIcon());
        $this->assertSame(Heroicon::OutlinedCheckCircle, OrderStatus::Completed->getIcon());
        $this->assertSame(Heroicon::OutlinedXCircle, OrderStatus::Cancelled->getIcon());
    }

    public function test_product_status_contract_methods_return_expected_values(): void
    {
        $this->assertSame('enabled', ProductStatus::Enabled->value);
        $this->assertSame('disabled', ProductStatus::Disabled->value);

        $this->assertSame(trans('product.status.enabled'), ProductStatus::Enabled->getLabel());
        $this->assertSame(trans('product.status.disabled'), ProductStatus::Disabled->getLabel());

        $this->assertSame(Color::Green, ProductStatus::Enabled->getColor());
        $this->assertSame(Color::Gray, ProductStatus::Disabled->getColor());
    }

    public function test_payment_methods_contract_methods_return_expected_values(): void
    {
        $this->assertSame(trans('system.payment_methods.cash'), PaymentMethods::Cash->getLabel());
        $this->assertSame(trans('system.payment_methods.cash_foreign'), PaymentMethods::CashForeign->getLabel());
        $this->assertSame(trans('system.payment_methods.debit_card'), PaymentMethods::DebitCard->getLabel());
        $this->assertSame(trans('system.payment_methods.credit_card'), PaymentMethods::CreditCard->getLabel());
        $this->assertSame(trans('system.payment_methods.pago_movil'), PaymentMethods::PagoMovil->getLabel());
        $this->assertSame(trans('system.payment_methods.zelle'), PaymentMethods::Zelle->getLabel());

        $this->assertSame(Color::Green, PaymentMethods::Cash->getColor());
        $this->assertSame(Color::Green, PaymentMethods::CashForeign->getColor());
        $this->assertSame(Color::Blue, PaymentMethods::DebitCard->getColor());
        $this->assertSame(Color::Blue, PaymentMethods::CreditCard->getColor());
        $this->assertSame(Color::Purple, PaymentMethods::PagoMovil->getColor());
        $this->assertSame(Color::Purple, PaymentMethods::Zelle->getColor());

        $this->assertSame(Heroicon::OutlinedCurrencyDollar, PaymentMethods::Cash->getIcon());
        $this->assertSame(Heroicon::OutlinedCurrencyDollar, PaymentMethods::CashForeign->getIcon());
        $this->assertSame(Heroicon::OutlinedCreditCard, PaymentMethods::DebitCard->getIcon());
        $this->assertSame(Heroicon::OutlinedCreditCard, PaymentMethods::CreditCard->getIcon());
        $this->assertSame(Heroicon::OutlinedDevicePhoneMobile, PaymentMethods::PagoMovil->getIcon());
        $this->assertSame(Heroicon::OutlinedDevicePhoneMobile, PaymentMethods::Zelle->getIcon());
    }
}
