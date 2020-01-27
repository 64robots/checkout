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
            'discount' => displayMoney($this->discount),
            'shipping' => displayMoney($this->shipping),
            'customer_email' => $this->customer_email,
            'shipping_first_name' => $this->shipping_first_name,
            'shipping_last_name' => $this->shipping_last_name,
            'shipping_address_line1' => $this->shipping_address_line1,
            'shipping_address_line2' => $this->shipping_address_line2,
            'shipping_address_city' => $this->shipping_address_city,
            'shipping_address_region' => $this->shipping_address_region,
            'shipping_address_zipcode' => $this->shipping_address_zipcode,
            'shipping_address_phone' => $this->shipping_address_phone,
            'billing_same' => (bool) $this->billing_same,
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_address_line1' => $this->billing_address_line1,
            'billing_address_line2' => $this->billing_address_line2,
            'billing_address_city' => $this->billing_address_city,
            'billing_address_region' => $this->billing_address_region,
            'billing_address_zipcode' => $this->billing_address_zipcode,
            'billing_address_phone' => $this->billing_address_phone,
            'customer_notes' => $this->customer_notes,
            'cart_items' => CartItemResource::collection($this->whenLoaded('cartItems')),
        ];
    }
}
