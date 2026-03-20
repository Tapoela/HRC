<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'quantity'
    ];

    public function increaseStock($productId, $qty)
    {
        $row = $this->where('product_id', $productId)->first();

        if ($row) {
            $this->update($row['id'], [
                'quantity' => $row['quantity'] + $qty
            ]);
        } else {
            $this->insert([
                'product_id' => $productId,
                'quantity' => $qty
            ]);
        }
    }
}