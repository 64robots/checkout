<?php
namespace R64\Checkout\Models;

// extends
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use R64\Checkout\Facades\Product;

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
        return $this->belongsTo(Product::getClassName(), Product::getForeignKey());
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

        $productForeignKey = Product::getForeignKey();

        $orderItem->order_id = Arr::get($data, 'order_id');
        $orderItem->{$productForeignKey} = Arr::get($data, $productForeignKey);
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

        $productForeignKey = Product::getForeignKey();

        $orderItem = static::makeOne([
            'order_id' => $orderId,
            $productForeignKey => $product->id,
            'cart_item_id' => $cartItem->id,
            'price' => $cartItem->price,
            'quantity' => $cartItem->quantity,
            'name' => $product->getName()
        ]);

        return $orderItem;
    }

    /***************************************************************************************
     ** HELPERS
     ***************************************************************************************/

    public function getFormattedPrice()
    {
        return displayMoney($this->price * $this->quantity);
    }
}
