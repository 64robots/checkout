<?php
namespace R64\Checkout\Models;

// extends
use R64\Checkout\Facades\Customer;
use R64\Checkout\Facades\Cart;
use Illuminate\Database\Eloquent\Model;

// includes
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use R64\Checkout\Helpers\Token;

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
     ** MODS
     ***************************************************************************************/

    public static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->token = Token::generate();
        });

        static::created(function ($order) {
            $order->order_number = $order->generateOrderNumber();
            $order->save();
        });
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function cart()
    {
        return $this->belongsTo(Cart::getClassName(), Cart::getForeignKey());
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::getClassName(), OrderItem::getForeignKey());
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

        $cart = Cart::getClassName()::byToken(Arr::get($data, 'cart_token'))->first();

        $customerForeignKey = Customer::getForeignKey();

        $order->{$customerForeignKey} = $purchase->{$customerForeignKey};
        $order->coupon_id = $cart->coupon_id;
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

        $order->billing_first_name = Arr::get($data, 'billing_first_name');
        $order->billing_last_name = Arr::get($data, 'billing_last_name');
        $order->billing_address_line1 = Arr::get($data, 'billing_address_line1');
        $order->billing_address_line2 = Arr::get($data, 'billing_address_line2');
        $order->billing_address_city = Arr::get($data, 'billing_address_city');
        $order->billing_address_region = Arr::get($data, 'billing_address_region');
        $order->billing_address_zipcode = Arr::get($data, 'billing_address_zipcode');
        $order->billing_address_phone = Arr::get($data, 'billing_address_phone');
        $order->status = Arr::get($data, 'status');
        $order->customer_notes = Arr::get($data, 'customer_notes');
        $order->admin_notes = Arr::get($data, 'admin_notes');
        $order->currency = config('checkout.currency.code');
        $order->{Cart::getForeignKey()} = $cart->id;
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

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function generateOrderNumber()
    {
        return $this->id;
    }

    public function hasDiscount()
    {
        return $this->discount > 0;
    }

    public function hasTax()
    {
        return $this->tax > 0;
    }

    public function hasShipping()
    {
        return $this->shipping > 0;
    }
}
