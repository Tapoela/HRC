<?php

namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'stock_levels';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'product_id',
        'location_id',
        'quantity'
    ];

    public function increaseStock($productId, $qty, $locationId = 1)
    {
        $row = $this
            ->where('product_id', $productId)
            ->where('location_id', $locationId)
            ->first();

        if ($row) {
            $this->update($row['id'], [
                'quantity' => $row['quantity'] + $qty
            ]);
        } else {
            $this->insert([
                'product_id' => $productId,
                'location_id' => $locationId,
                'quantity' => $qty
            ]);
        }
    }
}