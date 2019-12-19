<?php
namespace R64\Checkout\Models;

// extends
use R64\Checkout\Helpers\Price;
use Illuminate\Database\Eloquent\Model;

// includes
use R64\Checkout\Helpers\Token;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Checkout\Contracts\Product;

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

    public function user()
    {
        return $this->belongsTo(\R64\Checkout\Models\User::class, 'user_id');
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

        $cart->user_id = isset($data['user_id']) ? $data['user_id'] : null;
        $cart->items_subtotal = isset($data['items_subtotal']) ? $data['items_subtotal'] : 0;
        $cart->tax_rate = isset($data['tax_rate']) ? $data['tax_rate'] : null;
        $cart->tax = isset($data['tax']) ? $data['tax'] : 0;
        $cart->total = isset($data['total']) ? $data['total'] : 0;
        $cart->discount = isset($data['discount']) ? $data['discount'] : 0;
        $cart->ip_address = $data['ip_address'];
        $cart->save();

        if (Arr::get($data, 'product_id') !== null) {
            CartItem::makeOne($cart, [
                'product_id' => $data['product_id']
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
            return $cartItem->price * $cartItem->quantity;
        });

        $this->save();
    }

    public function setTotal()
    {
        $this->total = is_null($this->tax) ? $this->items_subtotal : $this->items_subtotal + $this->tax;
        $this->save();
    }

    public function setTaxRate(Product $product)
    {
        if (is_null($this->tax_rate) && $product->hasTaxRate()) {
            $this->tax_rate = $product->getTaxRate();
            $this->save();
        }
    }

    public function setTax()
    {
        $this->tax = Price::getTax($this->items_subtotal, $this->tax_rate);
        $this->save();
    }
}
