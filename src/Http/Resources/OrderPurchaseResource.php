<?php

namespace R64\Checkout\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderPurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'amount' => displayMoney($this->amount),
            'card_type' => $this->card_type,
            'card_last4' => $this->card_last4,
            'payment_method' => $this->payment_processor
        ];
    }
}
