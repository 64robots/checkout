<?php
namespace R64\Checkout\Models;

// extends
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use R64\Checkout\Helpers\Token;
use Illuminate\Database\Eloquent\Model;

// includes
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;
    use HasFactory;

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
            $cartItem->cart->setDiscount();
            $cartItem->cart->setTax();
            $cartItem->cart->setTotal();
        });
        static::updated(function ($cartItem) {
            $cartItem->cart->setItemSubtotal();
            $cartItem->cart->setDiscount();
            $cartItem->cart->setTax();
            $cartItem->cart->setTotal();
        });
        static::deleted(function ($cartItem) {
            $cartItem->cart->setItemSubtotal();
            $cartItem->cart->setDiscount();
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
        return $this->belongsTo(\R64\Checkout\Facades\Cart::getClassName(), \R64\Checkout\Facades\Cart::getForeignKey());
    }

    public function product()
    {
        return $this->belongsTo(\R64\Checkout\Facades\Product::getClassName(), \R64\Checkout\Facades\Product::getForeignKey());
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(Cart $cart, array $data)
    {
        $productForeignKey = \R64\Checkout\Facades\Product::getForeignKey();
        $product = \R64\Checkout\Facades\Product::getClassName()::findOrFail($data[$productForeignKey]);

        $cartItem = $cart->cartItems()->where($productForeignKey, $product->id)->first();

        if (!is_null($cartItem)) {
            $data['quantity'] = Arr::get($data, 'quantity') + $cartItem->quantity;
            $cartItem->updateMe($data);
            return $cartItem;
        }

        $cartItem = new CartItem;
        $cartItem->{\R64\Checkout\Facades\Cart::getForeignKey()} = $cart->id;
        $cartItem->{$productForeignKey} = $product->id;
        $cartItem->quantity = Arr::get($data, 'quantity', 1);
        $cartItem->price = $product->getPrice() * $cartItem->quantity;
        $cartItem->token = Token::generate();
        $cartItem->save();

        return $cartItem;
    }

    public function updateMe(array $data)
    {
        $newQuantity = !empty($data['quantity']) ? $data['quantity'] : $this->quantity;
        $this->price = $this->product->getPrice() * $newQuantity;
        $this->customer_note = Arr::has($data, 'customer_note') ? $data['customer_note'] : $this->customer_note;
        $this->quantity = $newQuantity;
        $this->save();
    }
}
