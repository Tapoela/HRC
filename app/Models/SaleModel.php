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
        'status'
    ];
}