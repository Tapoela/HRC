<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseAdmin extends BaseController
{
    protected $sidebarData = [];

    // Use initController so CI provides the Request/Response/Logger instances
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();

        $this->sidebarData['role'] = (int) $session->get('role_id');
        $this->sidebarData['name'] = $session->get('name');

        // ✅ Pending users counter
        $userModel = new UserModel();
        $this->sidebarData['pendingCount'] =
            $userModel->where('active',0)->countAllResults();
    }
}
