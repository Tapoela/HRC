<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileDraftModel extends Model
{
    protected $table = 'user_profile_draft';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id','idnumber','birthdate','cell','spouse_name','spouse_tel',
        'address','med_aid','med_no',
        'height','weight',
        'signature','signed_day','signed_at',
        'last_step'
    ];
}
