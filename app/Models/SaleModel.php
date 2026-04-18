<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table = 'sales';

    protected $allowedFields = [
        'location_id',
        'total',
        'payment_type_id',
        'tab_id',
        'user_id',
        'status',
        'card_reference',
        'created_at' // <-- ensure created_at can be set
    ];

    protected $useTimestamps = false; // We'll set created_at manually
}