<?php
namespace R64\Checkout\Models;

// extends
use Illuminate\Database\Eloquent\Model;

// includes
use R64\Checkout\Helpers\Token;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Checkout\Contracts\State;
use R64\Checkout\Facades\AddressSearch;
use R64\Checkout\Helpers\Price;

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
        return $this->belongsTo(\R64\Checkout\Facades\Customer::getClassName(), \R64\Checkout\Facades\Customer::getForeignKey());
    }

    public function cartItems()
    {
        return $this->hasMany(\R64\Checkout\Facades\CartItem::getClassName(), \R64\Checkout\Facades\Cart::getForeignKey());
    }

    public function coupon()
    {
        return $this->belongsTo(\R64\Checkout\Facades\Coupon::getClassName(), \R64\Checkout\Facades\Coupon::getForeignKey());
    }

    public function order()
    {
        return $this->hasOne(\R64\Checkout\Facades\Order::getClassName(), \R64\Checkout\Facades\Cart::getForeignKey());
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(array $data)
    {
        $cart = new self;

        $customerForeignKey = \R64\Checkout\Facades\Customer::getForeignKey();

        $cart->{$customerForeignKey} = isset($data[$customerForeignKey]) ? $data[$customerForeignKey] : null;
        $cart->customer_email = auth()->user() ? auth()->user()->email : null;
        $cart->items_subtotal = isset($data['items_subtotal']) ? $data['items_subtotal'] : 0;
        $cart->total = isset($data['total']) ? $data['total'] : 0;
        $cart->discount = isset($data['discount']) ? $data['discount'] : 0;
        $cart->ip_address = $data['ip_address'];
        $cart->save();

        $productForeignKey = \R64\Checkout\Facades\Product::getForeignKey();
        if (Arr::get($data, $productForeignKey) !== null) {
            \R64\Checkout\Facades\CartItem::getClassName()::makeOne($cart, $data);
        }

        return $cart;
    }

    public function updateMe(array $data)
    {
        $this->customer_notes = Arr::has($data, 'customer_notes') ? $data['customer_notes'] : $this->customer_notes;
        $this->customer_email = Arr::has($data, 'customer_email') ? $data['customer_email'] : $this->customer_email;
        $this->shipping_first_name = Arr::has($data, 'shipping_first_name') ? $data['shipping_first_name'] : $this->shipping_first_name;
        $this->shipping_last_name = Arr::has($data, 'shipping_last_name') ? $data['shipping_last_name'] : $this->shipping_last_name;
        $this->shipping_address_line1 = Arr::has($data, 'shipping_address_line1') ? $data['shipping_address_line1'] : $this->shipping_address_line1;
        $this->shipping_address_line2 = Arr::has($data, 'shipping_address_line2') ? $data['shipping_address_line2'] : $this->shipping_address_line2;
        $this->shipping_address_city = Arr::has($data, 'shipping_address_city') ? $data['shipping_address_city'] : $this->shipping_address_city;
        $this->shipping_address_region = Arr::has($data, 'shipping_address_region') ? $data['shipping_address_region'] : $this->shipping_address_region;
        $this->shipping_address_phone = Arr::has($data, 'shipping_address_phone') ? $data['shipping_address_phone'] : $this->shipping_address_phone;
        $this->shipping_address_zipcode = Arr::has($data, 'shipping_address_zipcode') ? $data['shipping_address_zipcode'] : $this->shipping_address_zipcode;

        $this->billing_same = Arr::has($data, 'billing_same') ? $data['billing_same'] : $this->billing_same;

        if (Arr::has($data, 'billing_same') && !$data['billing_same']) {
            $this->billing_first_name = null;
            $this->billing_last_name = null;
            $this->billing_address_line1 = null;
            $this->billing_address_line2 = null;
            $this->billing_address_city = null;
            $this->billing_address_region = null;
            $this->billing_address_zipcode = null;
            $this->billing_address_phone = null;
        } else if ($this->billing_same) {
            $this->billing_first_name = $this->shipping_first_name;
            $this->billing_last_name = $this->shipping_last_name;
            $this->billing_address_line1 = $this->shipping_address_line1;
            $this->billing_address_line2 = $this->shipping_address_line2;
            $this->billing_address_city = $this->shipping_address_city;
            $this->billing_address_region = $this->shipping_address_region;
            $this->billing_address_zipcode = $this->shipping_address_zipcode;
            $this->billing_address_phone = $this->shipping_address_phone;
        } else {
            $this->billing_first_name = Arr::has($data, 'billing_first_name') ? $data['billing_first_name'] : $this->billing_first_name;
            $this->billing_last_name = Arr::has($data, 'billing_last_name') ? $data['billing_last_name'] : $this->billing_last_name;
            $this->billing_address_line1 = Arr::has($data, 'billing_address_line1') ? $data['billing_address_line1'] : $this->billing_address_line1;
            $this->billing_address_line2 = Arr::has($data, 'billing_address_line2') ? $data['billing_address_line2'] : $this->billing_address_line2;
            $this->billing_address_city = Arr::has($data, 'billing_address_city') ? $data['billing_address_city'] : $this->billing_address_city;
            $this->billing_address_region = Arr::has($data, 'billing_address_region') ? $data['billing_address_region'] : $this->billing_address_region;
            $this->billing_address_zipcode = Arr::has($data, 'billing_address_zipcode') ? $data['billing_address_zipcode'] : $this->billing_address_zipcode;
            $this->billing_address_phone = Arr::has($data, 'billing_address_phone') ? $data['billing_address_phone'] : $this->billing_address_phone;
        }

        $this->save();
    }

    public function addCoupon(array $data)
    {
        if (isset($data['coupon_code'])) {
            $coupon = \R64\Checkout\Facades\Coupon::getClassName()::byCode($data['coupon_code'])->first();
            $this->{\R64\Checkout\Facades\Coupon::getForeignKey()} = $coupon->id;
            $this->discount = $coupon->calculateDiscount($this);
            $this->setTax();
            $this->setTotal();
        }
    }

    public function removeCoupon()
    {
        $coupon = $this->coupon;

        if (!$coupon) {
            return;
        }

        $this->{\R64\Checkout\Facades\Coupon::getForeignKey()} = null;
        $this->discount = 0;
        $this->setTax();
        $this->setTotal();
    }

    public function updateZipCode(array $data)
    {
        if (Arr::has($data, 'zipcode')) {
            $address = AddressSearch::getByPostalCode($data['zipcode'])->first();

            if (!is_null($address)) {
                /** @var State $states */
                $states = app(State::class);
                $this->shipping_address_city = $address->getCityName();
                $this->shipping_address_region = Arr::get($states->getByCode($address->getStateCode()), 'value');
                $this->save();
            }
        }
    }

    public function updateShipping(array $data)
    {

    }

    public function updateEmail(array $data)
    {

    }

    public function addOptions(array $options)
    {
        $this->options = $options;
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
        $this->total = $this->items_subtotal + $this->shipping - $this->discount + $this->tax;
        $this->save();
    }

    public function setDiscount()
    {
        if (!is_null($this->{\R64\Checkout\Facades\Coupon::getForeignKey()})) {
            $this->discount = $this->coupon->calculateDiscount($this);
            $this->save();
        }
    }

    public function setTax()
    {
        $this->tax = Price::getTax($this->items_subtotal - $this->discount, $this->tax_rate);
        $this->save();
    }

    public function hasDiscount()
    {
        return $this->discount > 0;
    }
}
