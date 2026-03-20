<?php

namespace App\Models;

use CodeIgniter\Model;

class DivisionModel extends Model
{
    protected $table = 'divisions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}