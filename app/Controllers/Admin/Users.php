<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Controllers\Admin\BaseAdmin;
use Dompdf\Dompdf;
use Dompdf\Options;

class Users extends BaseAdmin
{
    use DecodesHashId;

    public function index()
    {
        $users = (new \App\Models\UserModel())
                    ->select('users.*, roles.name as role')
                    ->join('roles','roles.id = users.role_id','left')
                    ->findAll();

        return view('admin/users/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = (new \App\Models\RoleModel())->findAll();

        return view('admin/users/create', [
            'roles' => $roles
        ]);
    }

    public function store()
    {
        $model = new UserModel();

        $model->insert([
            'name'      => $this->request->getPost('fname'),
            'surname'   => $this->request->getPost('surname'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role_id'   => $this->request->getPost('role_id'),
            'active'    => 1
        ]);

        return redirect()->to('/admin/users')
            ->with('success','User created');
    }

    public function approve($hash)
    {
        $id = $this->decodeHash($hash);
        $userModel = new \App\Models\UserModel();

        $user = $userModel->find($id);

        if(!$user){
            return redirect()->back();
        }

        // Activation token
        $token = bin2hex(random_bytes(32));

        // Approval timestamp
        $approvalTime = date('YmdHis');

        // Membership number
        $membershipNo = "HRC-" . $id . "-" . $approvalTime;

        $userModel->update($id,[
            'activation_token' => $token,
            'membership_no'    => $membershipNo,
            'approved_at'      => date('Y-m-d H:i:s'),
            'active'           => 0
        ]);

        // Send approval email
        $this->sendApprovalEmail(
            $user['email'],
            $user['name'],
            $token
        );

        return redirect()->back()->with('success','User approved. Activation email sent.');
    }

    private function sendApprovalEmail($email, $name, $token)
    {
        $emailService = \Config\Services::email();

        // 🔥 Build activation link here
        $link = site_url('activate/'.$token);

        $emailService->setTo($email);
        $emailService->setSubject('Heidelberg Rugby Club - Account Approved');

        $message = "
            Hi {$name},<br><br>
            Your registration has been approved.<br>
            Please activate your account below:<br><br>

            <a href='{$link}'>Activate Account</a><br><br>

            Regards,<br>
            Heidelberg Rugby Club
        ";

        $emailService->setMessage($message);

        $emailService->send();
    }

   public function unlock($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new \App\Models\UserModel();
        $db    = \Config\Database::connect();

        $adminId = session()->get('user_id');

        $user = $model->find($id);

        // 🔥 Safety check
        if(!$user){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'User not found'
            ]);
        }

        // Prevent double editing
        if(!empty($user['editing_by']) && $user['editing_by'] != $adminId){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Another admin is editing this profile'
            ]);
        }

        // Set editing lock
        $model->update($id,[
            'editing_by'=>$adminId,
            'editing_at'=>date('Y-m-d H:i:s')
        ]);

        // Audit log (safe)
        try{
            $db->table('tbl_admin_audit')->insert([
                'admin_id'=>$adminId,
                'action'=>'unlock_profile',
                'target_user_id'=>$id
            ]);
        }catch(\Throwable $e){
            log_message('error',$e->getMessage());
        }

        return $this->response->setJSON([
            'status'=>true,
            'admin'=>session()->get('name'),
            'time'=>date('H:i')
        ]);
    }

    public function printProfile($hash)
    {
        $id = $this->decodeHash($hash);
        $userModel = new \App\Models\UserModel();
        $data['user'] = $userModel->find($id);

        return view('admin/users/print_profile', $data);
    }

    public function getUser($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new \App\Models\UserModel();

        $user = $model->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)
                ->setJSON(['error' => 'User not found']);
        }

        return $this->response->setJSON($user); // ✅ send ALL columns
    }

    public function update($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new UserModel();
        $service = new \App\Libraries\UserProfileService();

        $adminId = session()->get('user_id');

        $user = $model->find($id);

        if(!$user){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'User not found'
            ]);
        }

        // 🔥 Prevent editing if another admin locked it
        if(!empty($user['editing_by']) && $user['editing_by'] != $adminId){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>'Profile locked by another admin'
            ]);
        }

        // 🔥 VALIDATION
        if(! $this->validate($service->rules())){
            return $this->response->setJSON([
                'status'=>false,
                'message'=>implode('<br>', $this->validator->getErrors())
            ]);
        }

        $data = array_intersect_key(
            $this->request->getPost(),
            array_flip($model->allowedFields)
        );

        // 🔥 Auto profile completed
        $data['profile_completed'] = $service->calculateProfileCompleted($data);

        // 🔥 Auto relock
        $data['editing_by'] = null;
        $data['editing_at'] = null;

        $model->update($id,$data);

        return $this->response->setJSON([
            'status'=>true,
            'message'=>'Profile saved successfully',
            'profile_completed'=>$data['profile_completed']
        ]);
    }

    public function pdf($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new \App\Models\UserModel();

        $user = $model->find($id);

        if(!$user){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Render your EXISTING print view
        $html = view('admin/users/print_profile', [
            'user' => $user
        ]);

        // DOMPDF Options (PRO SETTINGS)
        $options = new Options();
        $options->set('isRemoteEnabled', true); // allow logos + images
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Stream PDF to browser
        return $this->response
            ->setHeader('Content-Type','application/pdf')
            ->setBody($dompdf->output());
    }

    public function generateCard($hash)
    {
        $id = $this->decodeHash($hash);
        $db = \Config\Database::connect();

        $user = $db->table('users u')
            ->select('u.id AS id, 
                      u.name as pname, 
                      u.surname as surname, 
                      u.spouse_name as spouse_name, 
                      u.med_aid as med_aid, 
                      u.med_no as med_no, 
                      u.photo as photo, 
                      u.position_id as position_id, 
                      u.membership_no as membership_no, 
                      r.name, 
                      p.position_name')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->join('rugby_positions p', 'p.id = u.position_id', 'left')
            ->where('u.id', $id)
            ->get()
            ->getRow();

        if(!$user){
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
        }

        $verifyUrl = base_url('verify/'.$user->id);

        $qr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($verifyUrl);

        return view('admin/users/card',[
            'user'=>$user,
            'qr'=>$qr
        ]);
    }

    public function printCards()
    {
        $db = \Config\Database::connect();

        $users = $db->table('users u')
            ->select('u.id AS id, 
                      u.name as pname, 
                      u.surname as surname, 
                      u.spouse_name as spouse_name, 
                      u.med_aid as med_aid, 
                      u.med_no as med_no, 
                      u.photo as photo, 
                      u.position_id as position_id, 
                      u.membership_no as membership_no, 
                      r.name, 
                      p.position_name')
            ->join('roles r','r.id = u.role_id','left')
            ->join('rugby_positions p','p.id = u.position_id','left')
            ->where('u.active',1)
            ->get()
            ->getResult();

        foreach($users as &$user)
        {
            $verifyUrl = base_url('verify/'.$user->membership_no);

            $user->qr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" 
                         . urlencode($verifyUrl);
        }

        return view('admin/users/print_cards',[
            'users'=>$users
        ]);
    }

}
