<?php

namespace R64\Checkout\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'cart_token' => $this->token,
            'items_subtotal' => displayMoney($this->items_subtotal),
            'tax_rate' => displayTaxRate($this->tax_rate),
            'tax' => displayMoney($this->tax),
            'total' => displayMoney($this->total),
            'discount' => $this->discount,
            'user' => new \App\Http\Resources\UserResource($this->whenLoaded('user')),
            'cart_items' => CartItemResource::collection($this->whenLoaded('cartItems')),
        ];
    }
}
