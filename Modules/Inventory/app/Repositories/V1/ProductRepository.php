<?php

namespace Modules\Inventory\Repositories\V1;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Product;

class ProductRepository
{
    private static Model $model;
    public function __construct()
    {
        self::$model = Product::getModel();
    }
    public static function getPriceById($product_id)
    {
        return Product::where('id' , $product_id)->value('price');
    }
    public static function getQuantityById($id)
    {
        return Product::where('id' , $id)->value('quantity');
    }
}
