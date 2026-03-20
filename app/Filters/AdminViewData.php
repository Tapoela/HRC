<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;

class AdminViewData implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        $data = [
            'role' => (int) $session->get('role_id'),
            'name' => $session->get('name'),
            'pendingCount' => 0,
        ];

        // Load pending users count
        $userModel = new UserModel();
        $data['pendingCount'] =
            $userModel->where('active',0)->countAllResults();

        // 🔥 Share data globally with ALL views
        service('renderer')->setData($data);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing needed
    }
}
