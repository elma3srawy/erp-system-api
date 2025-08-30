<?php

namespace Modules\Inventory\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Traits\V1\Relationships\ProductRelations;


class ProductSuppliers extends Model
{
    use HasFactory;
    use ProductRelations;
    protected $table = 'products_suppliers';

    public $timestamps = false;
    protected static function newFactory()
    {
        return \Modules\Inventory\Database\Factories\ProductSuppliersFactory::new();
    }
}
