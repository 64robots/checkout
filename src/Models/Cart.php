<?php
namespace R64\Checkout\Models;

// extends
use R64\Checkout\Facades\Shipping;
use R64\Checkout\Helpers\Price;
use Illuminate\Database\Eloquent\Model;

// includes
use R64\Checkout\Helpers\Token;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Checkout\Facades\Product;
use R64\Checkout\Facades\Customer;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'carts';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    public $timestamps = true;

    /***************************************************************************************
     ** MODS
     ***************************************************************************************/

    public static function boot()
    {
        parent::boot();
        static::creating(function ($cart) {
            $cart->token = Token::generate();
        });
        static::deleting(function ($cart) {
            $cart->cartItems()->delete();
        });
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function customer()
    {
        return $this->belongsTo(Customer::getClassName(), Customer::getForeignKey());
    }

    public function cartItems()
    {
        return $this->hasMany(\R64\Checkout\Models\CartItem::class, 'cart_id');
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(array $data)
    {
        $cart = new self;

        $customerForeignKey = Customer::getForeignKey();

        $cart->{$customerForeignKey} = isset($data[$customerForeignKey]) ? $data[$customerForeignKey] : null;
        $cart->items_subtotal = isset($data['items_subtotal']) ? $data['items_subtotal'] : 0;
        $cart->tax_rate = config('checkout.tax_rate');
        $cart->tax = 0;
        $cart->total = isset($data['total']) ? $data['total'] : 0;
        $cart->discount = isset($data['discount']) ? $data['discount'] : 0;
        $cart->ip_address = $data['ip_address'];
        $cart->save();

        $productForeignKey = Product::getForeignKey();
        if (Arr::get($data, $productForeignKey) !== null) {
            CartItem::makeOne($cart, [
                $productForeignKey => $data[$productForeignKey]
            ]);
        }

        return $cart;
    }

    public function updateMe(array $data)
    {
        if (Arr::get($data, 'discount_code') !== null) {
            // Do something with discount code
        }

        $this->save();
    }

    /***************************************************************************************
     ** SCOPES
     ***************************************************************************************/

    public function scopeByToken($query, $token)
    {
        return $query->where('token', $token);
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/
    public function setItemSubtotal()
    {
        $this->items_subtotal = $this->cartItems->sum(function (CartItem $cartItem) {
            return $cartItem->price;
        });

        $this->save();
    }

    public function setTotal()
    {
        $this->total = is_null($this->tax) ? $this->items_subtotal : $this->items_subtotal + $this->tax;
        $this->save();
    }

    public function setTax()
    {
        $this->tax = Price::getTax($this->items_subtotal, $this->tax_rate);
        $this->save();
    }

    public function calculateTotal($shippingId)
    {
        $shippingMethod = Shipping::find($shippingId);

        return $this->total + Arr::get($shippingMethod, 'price', 0);
    }
}
