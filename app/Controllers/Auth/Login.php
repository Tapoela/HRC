<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\LoginLogModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $logModel  = new LoginLogModel();

        $user = $userModel->getByEmail($email);

        $success = false;

        if ($user && password_verify($password, $user['password'])) {

            $success = true;

            // Fetch division name
            $divisionName = '-';
            if (!empty($user['division_id'])) {
                $divisionModel = new \App\Models\DivisionModel();
                $division = $divisionModel->find($user['division_id']);
                if ($division && !empty($division['name'])) {
                    $divisionName = $division['name'];
                }
            }

            session()->set([
                'user_id'   => $user['id'],
                'name'      => $user['name'],
                'role_id'   => $user['role_id'],
                'role_name' => strtolower(trim($user['role'])),
                'division_id' => $user['division_id'],
                'division_name' => $divisionName,
                'logged_in' => true,
                'profile_completed' => $user['profile_completed'] ?? 0,
            ]);

            // update last login
            $userModel->update($user['id'], [
                'last_login' => date('Y-m-d H:i:s')
            ]);

            // Determine redirect
            $role = strtolower(trim($user['role']));

            if ($role === 'player') {

                if ((int)$user['profile_completed'] === 0) {
                    $redirect = '/player/profile/setup';
                } else {
                    $redirect = '/player/dashboard';
                }

            } elseif ($role === 'coach') {

                $redirect = '/coach/dashboard';

            } else {

                // All admin + approval roles
                $adminRoles = ['admin','chairman','treasurer','vice chairman'];

                if (in_array($role, $adminRoles)) {
                    $redirect = '/admin/dashboard';
                } else {
                    $redirect = '/dashboard-default';
                }
            }

        } else {
            $redirect = '/login';
        }

        // log attempt
        $logModel->insert([
            'user_id'    => $user['id'] ?? null,
            'email'      => $email,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
            'success'    => $success ? 1 : 0,
        ]);

        if (!$success) {
            return redirect()->to('/login')->with('error', 'Invalid login');
        }

        return redirect()->to($redirect);

    }

    public function logout()
    {
        $session = session();

        // destroy everything
        $session->destroy();

        return redirect()->to('/login')
            ->with('success','You have been logged out.');
    }
}
