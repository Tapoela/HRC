<?php
namespace App\Models;

use CodeIgniter\Model;

class SpecialModel extends Model
{
    protected $table = 'specials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'price', 'active'];
    protected $returnType = 'array';
}
