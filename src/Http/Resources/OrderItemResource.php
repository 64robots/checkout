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
        return [
            'name' => $this->name,
            'price' => displayMoney($this->price),
            'quantity' => $this->quantity,
            'product' => new ProductResource($this->whenLoaded('product'))
        ];
    }
}
