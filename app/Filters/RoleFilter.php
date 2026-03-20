<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(!session()->get('logged_in')){
            return redirect()->to('/login');
        }

        if(empty($arguments)){
            return;
        }

        // role name passed from route
        $requiredRole = strtolower($arguments[0]);

        // 🔥 fetch role name from DB using role_id
        $roleId = session()->get('role_id');

        if(!$roleId){
            return redirect()->to('/login');
        }

        $roleModel = new \App\Models\RoleModel();

        $role = $roleModel->find($roleId);

        if(!$role || strtolower($role['name']) !== $requiredRole){
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){}
}
