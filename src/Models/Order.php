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
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'delivery_date'];
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

    public function orderPurchase()
    {
        return $this->hasOne(OrderPurchase::class);
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(OrderPurchase $purchase, array $data)
    {
        $order = new self;
        $cart = Cart::byToken(Arr::get($data, 'cart_token'))->first();

        $customerForeignKey = Customer::getForeignKey();
        $order->{$customerForeignKey} = $purchase->{$customerForeignKey};
        $order->customer_email = !empty($data['customer_email']) ? $data['customer_email'] : $purchase->email;
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

        $order->cart_id = $cart->id;
        $order->items_total = $cart->items_subtotal;
        $order->tax = $cart->tax;
        $order->tax_rate = $cart->tax_rate;
        $order->discount = $cart->discount;
        $order->shipping = $cart->shipping;
        $order->total = $cart->total;
        $order->save();

        $cart->cartItems->each(function (CartItem $cartItem) use ($order) {
            OrderItem::makeOneFromCartItem($cartItem, $order->id);
        });

        $purchase->order()->associate($order);
        $purchase->save();

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
