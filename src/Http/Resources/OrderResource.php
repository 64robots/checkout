<?php

namespace R64\Checkout\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'token' => $this->token,
            'order_number' => $this->order_number,
            'customer_email' => $this->customer_email,
            'items_total' => displayMoney($this->items_total),
            'shipping' => displayMoney($this->shipping),
            'total' => displayMoney($this->total),
            'tax_rate' => displayTaxRate($this->tax_rate),
            'tax' => displayMoney($this->tax),
            'discount' => displayMoney($this->discount),
            'currency' => $this->currency,
            'shipping_first_name' => $this->shipping_first_name,
            'shipping_last_name' => $this->shipping_last_name,
            'shipping_address_line1' => $this->shipping_address_line1,
            'shipping_address_line2' => $this->shipping_address_line2,
            'shipping_address_city' => $this->shipping_address_city,
            'shipping_address_region' => $this->shipping_address_region,
            'shipping_address_zipcode' => $this->shipping_address_zipcode,
            'shipping_address_phone' => $this->shipping_address_phone,
            'billing_address_line1' => $this->billing_address_line1,
            'billing_address_line2' => $this->billing_address_line2,
            'billing_address_city' => $this->billing_address_city,
            'billing_address_region' => $this->billing_address_region,
            'billing_address_zipcode' => $this->billing_address_zipcode,
            'billing_address_phone' => $this->billing_address_phone,
            'status' => $this->status,
            'customer_notes' => $this->customer_notes,
            'admin_notes' => $this->admin_notes,
            'order_items' => OrderItemResource::collection($this->whenLoaded('order_items')),
            'order_purchase' => new OrderPurchaseResource($this->whenLoaded('orderPurchase')),
            'created_at' => $this->created_at->format('M, d, Y')
        ];
    }
}
