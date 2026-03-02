<?php

namespace App\Actions\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CreatePayment
{
    public static function execute(array $data): Payment
    {
        $data['creator_id'] = Auth::id();

        return Payment::query()->create($data);
    }
}
