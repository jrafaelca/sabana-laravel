<?php

namespace App\Actions;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CreatePaymentAction
{
    public static function handle(array $data): Payment
    {
        $data['creator_id'] = Auth::id();

        return Payment::query()->create($data);
    }
}
