<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'suppliers';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'contact_person',
        'phone',
        'email',
        'active'
    ];

    protected $returnType = 'array';

    protected $useTimestamps = false;
}