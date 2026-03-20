<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
    protected $table = 'results';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'team',
        'opponent',
        'team_score',
        'opponent_score',
        'match_date'
    ];
}

