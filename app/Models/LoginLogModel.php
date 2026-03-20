<?php 

namespace App\Models;

use CodeIgniter\Model;

class LoginLogModel extends Model
{
    protected $table = 'login_logs';
    protected $allowedFields = [
        'user_id', 'email', 'ip_address', 'user_agent', 'success'
    ];
}
