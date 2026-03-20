<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class BaseAdmin extends BaseController
{
    protected $sidebarData = [];

    public function __construct()
    {
        $session = session();

        $this->sidebarData['role'] = (int) $session->get('role_id');
        $this->sidebarData['name'] = $session->get('name');

        // ✅ Pending users counter
        $userModel = new UserModel();
        $this->sidebarData['pendingCount'] =
            $userModel->where('active',0)->countAllResults();
    }
}
