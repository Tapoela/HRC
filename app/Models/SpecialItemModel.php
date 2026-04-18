<?php
namespace App\Models;

use CodeIgniter\Model;

class SpecialItemModel extends Model
{
    protected $table = 'special_items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['special_id', 'category_id', 'product_id', 'qty', 'slot_label'];
    protected $returnType = 'array';
}
