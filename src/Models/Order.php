<?php
namespace R64\Checkout\Models;

// extends
use R64\Checkout\Facades\Customer;
use R64\Checkout\Helpers\Price;
use Illuminate\Database\Eloquent\Model;

// includes
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use R64\Checkout\Facades\Shipping;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    public $timestamps = true;

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::getClassName(), Customer::getForeignKey());
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(array $data)
    {
        $order = new self;

        $order->items_total = 0;

        $order->shipping_total = Arr::get(Shipping::find(Arr::get($data, 'shipping_id')), 'price');
        $order->tax_total = 0;
        $order->total = 0;

        $customerForeignKey = Customer::getForeignKey();

        $order->{$customerForeignKey} = Arr::get($data, $customerForeignKey);
        $order->customer_email = Arr::get($data, 'customer_email');
        $order->shipping_first_name = Arr::get($data, 'shipping_first_name');
        $order->shipping_last_name = Arr::get($data, 'shipping_last_name');
        $order->shipping_address_line1 = Arr::get($data, 'shipping_address_line1');
        $order->shipping_address_line2 = Arr::get($data, 'shipping_address_line2');
        $order->shipping_address_city = Arr::get($data, 'shipping_address_city');
        $order->shipping_address_city = Arr::get($data, 'shipping_address_city');
        $order->shipping_address_region = Arr::get($data, 'shipping_address_region');
        $order->shipping_address_zipcode = Arr::get($data, 'shipping_address_zipcode');
        $order->shipping_address_phone = Arr::get($data, 'shipping_address_phone');
        $order->billing_address_line1 = Arr::get($data, 'billing_address_line1');
        $order->billing_address_line2 = Arr::get($data, 'billing_address_line2');
        $order->billing_address_city = Arr::get($data, 'billing_address_city');
        $order->billing_address_region = Arr::get($data, 'billing_address_region');
        $order->billing_address_zipcode = Arr::get($data, 'billing_address_zipcode');
        $order->billing_address_phone = Arr::get($data, 'billing_address_phone');
        $order->status = Arr::get($data, 'status');
        $order->customer_notes = Arr::get($data, 'customer_notes');
        $order->admin_notes = Arr::get($data, 'admin_notes');
        $order->save();

        $cart = Cart::byToken(Arr::get($data, 'cart_token'))->first();

        if (!is_null($cart)) {
            $cart->cartItems->each(function (CartItem $cartItem) use ($order) {
                OrderItem::makeOneFromCartItem($cartItem, $order->id);
            });

            $order->cart_id = $cart->id;

            // @TODO how to calculate shipping ?
            $order->items_total = $cart->items_subtotal;
            $order->tax_total = $cart->tax;
            $order->tax_rate = $cart->tax_rate;
            $order->total = $cart->total + $order->shipping_total;
        } else {
            $order->items_total = collect(Arr::get($data, 'order_items'))->map(function ($orderItem) use ($order) {
                return OrderItem::makeOne([
                    'order_id' => $order->id,
                    'price' => $orderItem['price'],
                    'quantity' => Arr::get($orderItem, 'quantity', 1),
                    'name' => $orderItem['name']
                ]);
            })->sum(function (OrderItem $orderItem) {
                return $orderItem->price * $orderItem->quantity;
            });

            $order->tax_total = Arr::has($data, 'tax_rate') ? Price::getTax($order->items_total, Arr::get($data, 'tax_rate')) : 0;
            $order->tax_rate = Arr::get($data, 'tax_rate', null);
            $order->total = $order->items_total + $order->shipping_total + $order->tax_total;
        }
        $order->save();

        return $order;
    }

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopeByEmail($query, string $email)
    {
        return $query->where('customer_email', $email);
    }
}
