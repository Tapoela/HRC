<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'po_number',
        'supplier_id',
        'order_date',
        'status',
        'total_amount',
        'created_by',
        'approval_status'
    ];
}