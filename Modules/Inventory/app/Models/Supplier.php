<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Inventory\Database\Factories\SupplierFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name',
        'contact_name',
        'contact_email',
        'phone'
    ];

    protected static function newFactory()
    {
        return SupplierFactory::new();
    }
}
