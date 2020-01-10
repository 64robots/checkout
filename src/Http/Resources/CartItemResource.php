<?php

namespace R64\Checkout\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'cart_item_token' => $this->token,
            'price' => displayMoney($this->price),
            'quantity' => $this->quantity,
            'customer_note' => $this->customer_note,
            'product' => new ProductResource($this->whenLoaded('product'))
        ];
    }
}
