<?php

namespace App\Models;

use CodeIgniter\Model;

class FixtureModel extends Model
{
    protected $table = 'fixtures';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'team',
        'opponent',
        'match_date',
        'match_time',   // 👈 add
        'venue',
        'venue_name'    // 👈 add
    ];

}
