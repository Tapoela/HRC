<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [

        // BASIC
        'role_id',
        'name',
        'surname',        // 🔥 MISSING
        'email',
        'password',
        'active',
        'last_login',
        'activation_token',
        'photo',
        'photo_thumb',
        'division_id',
        'position_id',

        // PROFILE DATA
        'idnumber',
        'birthdate',
        'address',
        'cell',
        'spouse_name',
        'spouse_tel',
        'height',
        'weight',
        'med_aid',
        'med_no',
        'signature',
        'signed_day',
        'signed_at',
        'Position',
        'team',

        // EDIT LOCK
        'editing_by',
        'editing_at',

        // STATUS
        'profile_completed'
    ];

    protected $returnType = 'array';

    public function getByEmail(string $email)
    {
        return $this->select('users.*, roles.name AS role')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->where('users.active', 1)
            ->first();
    }
}
