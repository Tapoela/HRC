<?php

namespace App\Models;

use CodeIgniter\Model;

class TabModel extends Model
{
    protected $table = 'tabs';

    protected $allowedFields = [
        'name',
        'phone',
        'vehicle',
        'member_id',
        'opened_by',
        'location_id',
        'balance',
        'status'
    ];
} 