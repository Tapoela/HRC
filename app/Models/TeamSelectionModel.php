<?php
namespace App\Models;

use CodeIgniter\Model;

class TeamSelectionModel extends Model
{
    protected $table = 'team_selections';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fixture_id',
        'team_name',
        'players', // JSON array of player info
        'coach1_id',
        'coach2_id',
        'manager_id',
        'created_by',
        'updated_by',
        'notes',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $returnType = 'array';
    protected $validationRules = [
        'fixture_id' => 'required|integer',
        'team_name' => 'required|string',
        'players' => 'required',
        'coach1_id' => 'permit_empty|integer',
        'coach2_id' => 'permit_empty|integer',
        'manager_id' => 'permit_empty|integer',
    ];

    public function insert($data = null, bool $returnID = true)
    {
        return parent::insert($data, $returnID);
    }
}
