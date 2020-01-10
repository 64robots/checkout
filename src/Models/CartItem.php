<?php
namespace R64\Checkout\Models;

// extends
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Checkout\Helpers\Token;
use Illuminate\Database\Eloquent\Model;
use R64\Checkout\Facades\Product;
// includes
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $table = 'cart_items';
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
        static::created(function ($cartItem) {
            $cartItem->cart->setItemSubtotal();
            $cartItem->cart->setTaxRate($cartItem->product);
            $cartItem->cart->setTax();
            $cartItem->cart->setTotal();
        });
        static::updated(function ($cartItem) {
            $cartItem->cart->setItemSubtotal();
            $cartItem->cart->setTaxRate($cartItem->product);
            $cartItem->cart->setTax();
            $cartItem->cart->setTotal();
        });
        static::deleted(function ($cartItem) {
            $cartItem->cart->setItemSubtotal();
            $cartItem->cart->setTax();
            $cartItem->cart->setTotal();
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
        return $this->belongsTo(\R64\Checkout\Models\Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::getClassName(), Product::getForeignKey());
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(Cart $cart, array $data)
    {
        $productForeignKey = Product::getForeignKey();
        $product = (Product::getModel())->findOrFail($data[$productForeignKey]);

        $cartItem = new CartItem;
        $cartItem->cart_id = $cart->id;
        $cartItem->{$productForeignKey} = $product->id;
        $cartItem->price = $product->getPrice();
        $cartItem->quantity = Arr::get($data, 'quantity', 1);
        $cartItem->token = Token::generate();
        $cartItem->save();

        return $cartItem;
    }

    public function updateMe(array $data)
    {
        $this->price = $this->product->getPrice() * Arr::get($data, 'quantity');
        $this->customer_note = Arr::get($data, 'customer_note');
        $this->quantity = Arr::get($data, 'quantity', $this->quantity);
        $this->save();
    }
}
