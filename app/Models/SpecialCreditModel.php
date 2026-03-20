<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecialCreditModel extends Model
{
    protected $table = 'special_credits';

    protected $allowedFields = [
        'sale_id',
        'special_id',
        'total_drinks',
        'remaining_drinks'
    ];
}