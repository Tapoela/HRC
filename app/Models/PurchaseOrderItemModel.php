<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderItemModel extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'po_id',
        'product_id',
        'qty_ordered',
        'cost_price',
        'received_qty',
        'location_id',
    ];
}