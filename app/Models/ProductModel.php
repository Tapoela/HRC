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
        'active',
        'unit_size_ml',
        'serving_size_ml',
        'stock'
    ];

    /**
     * Increment product stock by $qty
     */
    public function addStock($product_id, $qty)
    {
        $product = $this->find($product_id);
        if (!$product) return false;
        $newStock = (int)($product['stock'] ?? 0) + (int)$qty;
        return $this->update($product_id, ['stock' => $newStock]);
    }
}