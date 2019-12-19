<?php
namespace R64\Checkout\Helpers;

use R64\Checkout\Models\Product;

class ProductPriceHandler
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
