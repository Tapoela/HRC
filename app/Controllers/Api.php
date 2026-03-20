<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    public function login()
    {
        $data = $this->request->getJSON(true);

        return $this->response->setJSON([
            'success' => true,
            'name' => 'Test User',
            'emp_code' => $data['emp_code'] ?? '',
            'userId' => 1
        ]);
    }
}