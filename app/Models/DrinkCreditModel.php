<?php

namespace App\Models;

use CodeIgniter\Model;

class DrinkCreditModel extends Model
{
    protected $table = 'drink_credits';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'phone',
        'total_bought',
        'total_redeemed',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
