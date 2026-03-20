<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\UserModel;
//use App\Models\SettingModel;

class Register extends BaseController
{
    protected $protectFields = true;

    protected $helpers = ['honeypot'];

    public function index()
    {
        $db = \Config\Database::connect();

        $divisionModel = new \App\Models\DivisionModel();

        $roles = $db->table('roles')
        ->select('id, name')
        ->whereIn('name', ['Player','Coach'])
        ->orderBy('name','ASC')
        ->get()
        ->getResult();

        $divisions = $divisionModel
        ->orderBy('name','ASC')
        ->findAll();

        return view('public/register', [
            'roles' => $roles,
            'divisions' => $divisions
        ]);
    }

    public function store()
    {

        $role_id = $this->request->getPost('role_id');

        $rules = [
            'fname'       => 'required|min_length[3]',
            'email'       => 'required|valid_email|is_unique[users.email]',
            'password'    => 'required|min_length[6]',
            'human_check' => 'required|in_list[rugby,Rugby]',
            'role_id'     => 'required'
        ];

        if ($role_id == 3) {
            $rules['division_id'] = 'required';
        }


        if (!$this->validate($rules)) {

            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        /* ---------------- MODELS ---------------- */

        $userModel     = new \App\Models\UserModel();
        $settingsModel = new \App\Models\SettingsModel();

        /* ---------------- DATA ---------------- */

        $data = [
            'name'        => $this->request->getPost('fname'),
            'surname'     => $this->request->getPost('surname'),
            'email'       => $this->request->getPost('email'),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role_id'     => $role_id,
            'division_id' => $this->request->getPost('division_id'),
            'active'      => 0
        ];

        /* ---------------- TOKEN ---------------- */

        $token = bin2hex(random_bytes(32));

        $data['activation_token']   = $token;
        $data['activation_expires'] = date('Y-m-d H:i:s', strtotime('+24 hours'));

        /* ---------------- INSERT USER ---------------- */

        $insert = $userModel->insert($data);

        if (!$insert) {

            log_message('error', 'User registration insert failed: '.json_encode($userModel->errors()));

            return redirect()->back()
                ->withInput()
                ->with('error','Registration failed. Please contact support.');
        }

        /* ---------------- DETERMINE NOTIFY EMAIL ---------------- */


        $notifyEmail = $this->resolveDepartmentEmail(
            $role_id,
            $data['division_id']
        );

        
        if (!$notifyEmail) {
            $notifyEmail = $settingsModel->getValue('admin_notify_email');
        }

        /* ---------------- SEND EMAILS ---------------- */

        try {

            $this->sendRegistrationEmails(
                $data['email'],
                $data['name'],
                $data['surname'],
                $notifyEmail,
                $token
            );

        } catch (\Throwable $e) {

            log_message('error', 'Registration email failure: '.$e->getMessage());
        }

        /* ---------------- SUCCESS ---------------- */

        return redirect()->to('/register')
            ->with('success','Registration submitted. You will receive an activation email once approved.');
    }

    private function sendRegistrationEmails($userEmail,$name,$sname,$adminEmail,$token)
    {
        $email = \Config\Services::email();

        $activationLink = site_url('activate/'.$token); // ⭐ THIS WAS MISSING

        $email->setFrom('admin@heidelbergrugbyclub.co.za','Heidelberg Rugby Club');

        /* PLAYER EMAIL */
        $email->setTo($userEmail);
        $email->setSubject('Heidelberg Rugby Club Registration Received');

        $email->setMessage("
            Hi {$name} {$sname},<br><br>

            Thank you for registering with Heidelberg Rugby Club.<br><br>

            Please activate your account by clicking below:<br>
            <a href='{$activationLink}'>Activate My Account</a><br><br>

            Kind Regards,<br>
            Heidelberg Rugby Club
        ");

        if (! $email->send()) {
            log_message('error','Player email failed: '.$email->printDebugger(['headers','subject']));
        }

        /* ADMIN EMAIL */
        $email->clear();

        $email->setTo($adminEmail);
        $email->setSubject('New Player Registration');

        $email->setMessage("
            A new player has registered.<br><br>
            Name: {$name} {$sname}<br>
            Email: {$userEmail}<br>
        ");

        if (! $email->send()) {
            log_message('error','Admin email failed: '.$email->printDebugger(['headers','subject']));
        }
    }

    private function resolveDepartmentEmail($role_id, $division_id)
    {
        $db = \Config\Database::connect();
        $settingsModel = new \App\Models\SettingsModel();

        /*
        COACH REGISTRATION
        ------------------
        Always notify admin
        */
        if($role_id == 2){ // Coach
            return $settingsModel->getValue('admin_notify_email');
        }

        /*
        PLAYER REGISTRATION
        -------------------
        Route by division
        */
        if($role_id == 3 && $division_id){

            $division = $db->table('divisions')
                ->select('registration_notify_email')
                ->where('id', $division_id)
                ->get()
                ->getRow();

            if($division && !empty($division->registration_notify_email)){
                return $division->registration_notify_email;
            }
        }

        /*
        FALLBACK
        --------
        */
        return $settingsModel->getValue('admin_notify_email');
    }

    public function checkEmail()
    {
        $email = $this->request->getPost('email');

        $exists = (new \App\Models\UserModel())
            ->where('email', $email)
            ->first();

        return $this->response->setJSON([
            'exists' => $exists ? true : false
        ]);
    }

}
