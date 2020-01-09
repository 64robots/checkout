<?php
namespace R64\Checkout\Models;

// extends
use R64\Checkout\Helpers\Token;
use Illuminate\Database\Eloquent\Model;

// includes
use Illuminate\Database\Eloquent\SoftDeletes;
use R64\Checkout\ProductRepository;

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
        return $this->belongsTo(get_class(app(ProductRepository::class)->getModel()), 'product_id');
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(Cart $cart, array $data)
    {
        /** @var ProductRepository $productRepository */
        $productRepository = app(ProductRepository::class);
        $product = $productRepository->getModel();
        $product = $product->findOrFail($data['product_id']);

        $cartItem = new CartItem;
        $cartItem->cart_id = $cart->id;
        $cartItem->product_id = $product->id;
        $cartItem->price = $product->getPrice();
        $cartItem->quantity = isset($data['quantity']) ? $data['quantity'] : 1;
        $cartItem->token = Token::generate();
        $cartItem->save();

        return $cartItem;
    }

    public function updateMe(array $data)
    {
        $this->price = $this->product->getPrice() * $data['quantity'];
        $this->quantity = $data['quantity'];
        $this->save();
    }
}
