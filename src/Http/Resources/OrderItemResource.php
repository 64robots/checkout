<?php

namespace R64\Checkout\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $productResource = config('checkout.product_resource');

        return [
            'name' => $this->name,
            'price' => displayMoney($this->price),
            'quantity' => $this->quantity,
            'customer_note' => $this->customer_note,
            'product' => new $productResource($this->whenLoaded('product'))
        ];
    }
}
