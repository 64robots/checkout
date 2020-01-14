<?php

namespace R64\Checkout\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use R64\Checkout\Models\OrderPurchase;

class NewOrderPurchase
{
    use Dispatchable, SerializesModels;

    /** @var OrderPurchase */
    private $order;

    /**
     * @param OrderPurchase $order
     */
    public function __construct(OrderPurchase $order)
    {
        $this->order = $order;
    }
}
