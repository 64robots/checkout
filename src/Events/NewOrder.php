<?php

namespace R64\Checkout\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use R64\Checkout\Models\Order;

class NewOrder
{
    use Dispatchable, SerializesModels;

    /** @var Order */
    private $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
