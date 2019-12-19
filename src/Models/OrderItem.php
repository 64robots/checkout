<?php
namespace R64\Checkout\Models;

// extends
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use R64\Checkout\Models\CartItem;
use R64\Checkout\Models\Product;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $guarded = ['id'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
    public $timestamps = true;

    use SoftDeletes;

    /***************************************************************************************
     ** RELATIONS
     ***************************************************************************************/

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }

    /***************************************************************************************
     ** CRUD
     ***************************************************************************************/

    public static function makeOne(array $data)
    {
        $orderItem = new self;

        $orderItem->order_id = Arr::get($data, 'order_id');
        $orderItem->product_id = Arr::get($data, 'product_id');
        $orderItem->cart_item_id = Arr::get($data, 'cart_item_id');
        $orderItem->price = Arr::get($data, 'price');
        $orderItem->quantity = Arr::get($data, 'quantity');
        $orderItem->name = Arr::get($data, 'name');
        $orderItem->save();

        return $orderItem;
    }

    public static function makeOneFromCartItem(CartItem $cartItem, $orderId)
    {
        $product = $cartItem->product;

        return static::makeOne([
            'order_id' => $orderId,
            'product_id' => $product->id,
            'cart_item_id' => $cartItem->id,
            'price' => $product->getPrice(),
            'quantity' => $cartItem->quantity,
            'name' => $product->getName()
        ]);
    }

}
