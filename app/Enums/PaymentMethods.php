<?php

namespace App\Enums;

use BackedEnum;
use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum PaymentMethods: string implements HasLabel, HasColor, HasIcon
{
    case Cash = 'cash';
    case CashForeign = 'cash_foreign';
    case DebitCard = 'debit_card';
    case CreditCard = 'credit_card';
    case PagoMovil = 'pago_movil';
    case Zelle = 'zelle';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Cash => trans('system.payment_methods.cash'),
            self::CashForeign => trans('system.payment_methods.cash_foreign'),
            self::DebitCard => trans('system.payment_methods.debit_card'),
            self::CreditCard => trans('system.payment_methods.credit_card'),
            self::PagoMovil => trans('system.payment_methods.pago_movil'),
            self::Zelle => trans('system.payment_methods.zelle'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Cash => Color::Green,
            self::CashForeign => Color::Green,
            self::DebitCard => Color::Blue,
            self::CreditCard => Color::Blue,
            self::PagoMovil => Color::Purple,
            self::Zelle => Color::Purple,
        };
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Cash => Heroicon::OutlinedCurrencyDollar,
            self::CashForeign => Heroicon::OutlinedCurrencyDollar,
            self::DebitCard => Heroicon::OutlinedCreditCard,
            self::CreditCard => Heroicon::OutlinedCreditCard,
            self::PagoMovil => Heroicon::OutlinedDevicePhoneMobile,
            self::Zelle => Heroicon::OutlinedDevicePhoneMobile,
        };
    }
}
