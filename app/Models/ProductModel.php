<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'category_id',
        'type',
        'unit_type',
        'product_type',
        'sell_price',
        'cost_price',
        'track_stock',
        'active'
    ];
}