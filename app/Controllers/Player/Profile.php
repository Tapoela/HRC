<?php

namespace App\Controllers\Player;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function setup()
    {
        $settings   = new \App\Models\SettingsModel();
        $userModel  = new UserModel();
        $draftModel = new \App\Models\UserProfileDraftModel();

        $db = \Config\Database::connect();

        $userId = session()->get('user_id');

        if(!$userId){
            return redirect()->to('/login');
        }

        $user = $userModel->find($userId);


        $positions = $db->table('rugby_positions')
            ->select('id, position_name')
            ->orderBy('id','ASC')
            ->get()
            ->getResult();

        $draft = $draftModel
                    ->where('user_id',$userId)
                    ->first();

        return view('players/profile_setup',[
            'fees' => [
                'amount' => $settings->getSetting('membership_fee'),
                'year'   => date('Y')
            ],
            'user'=>$user,
            'draft'=>$draft,
            'positions' => $positions
        ]);
    }

    private function fixImageOrientation($file)
    {
        if (!function_exists('exif_read_data')) {
            return;
        }

        $exif = @exif_read_data($file);

        if (!$exif || !isset($exif['Orientation'])) {
            return;
        }

        $image = imagecreatefromjpeg($file);

        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }

        imagejpeg($image, $file, 90);
    }

    public function save()
    {
        //dd($this->request->getFiles());

        $userModel = new UserModel();
        $userId    = session()->get('user_id');

        if(!$userId){
            return redirect()->to('/login');
        }

        $data = [

            'idnumber'     => $this->request->getPost('idnumber'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'address'      => $this->request->getPost('address'),
            'cell'         => $this->request->getPost('cell'),

            'spouse_name'  => $this->request->getPost('spouse_name'),
            'spouse_tel'   => $this->request->getPost('spouse_tel'),

            'height'       => $this->request->getPost('height'),
            'weight'       => $this->request->getPost('weight'),

            'med_aid'      => $this->request->getPost('med_aid'),
            'med_no'       => $this->request->getPost('med_no'),

            'signature'    => $this->request->getPost('signature'),
            'signed_day'   => $this->request->getPost('signed_day'),
            'signed_at'    => $this->request->getPost('signed_at'),
            'position_id'    => $this->request->getPost('position_id'),


            'profile_completed' => 1
        ];

        $rules = [

            'idnumber'   => 'required|min_length[13]|max_length[13]',
            'birthdate'  => 'required',
            'address'    => 'required',
            'cell'       => 'required',
            'height'     => 'required',
            'signature'  => 'required',
            'signed_day' => 'required',
            'signed_at'  => 'required',
            'position_id'=> 'required'
        ];

        if(!$this->validate($rules)){
            return redirect()->back()
                ->withInput()
                ->with('error',$this->validator->listErrors());
        }

       /* =========================
           HANDLE PROFILE PHOTO
        ========================= */

        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && !$file->hasMoved()) {

            $uploadPath = FCPATH . 'uploads/players/' . $userId . '/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $filename = 'avatar.jpg';
            $thumb    = 'avatar_thumb.jpg';

            $file->move($uploadPath, $filename, true);

            $this->fixImageOrientation($uploadPath . $filename);

            // Resize main avatar
            \Config\Services::image()
                ->withFile($uploadPath . $filename)
                ->fit(500, 500, 'center')
                ->save($uploadPath . $filename, 75);

            // Create thumbnail
            \Config\Services::image()
                ->withFile($uploadPath . $filename)
                ->fit(100, 100, 'center')
                ->save($uploadPath . $thumb, 75);

            $data['photo'] = 'players/' . $userId . '/' . $filename;
            $data['photo_thumb'] = 'players/' . $userId . '/' . $thumb;

            session()->set('photo_thumb', $data['photo_thumb']);

            /* =========================
               COMPRESS MAIN IMAGE
            ========================= */

            \Config\Services::image()
                ->withFile($uploadPath . $filename)
                ->fit(500, 500, 'center')
                ->save($uploadPath . $filename, 70); // quality %

            /* =========================
               CREATE NAVBAR THUMBNAIL
            ========================= */

            \Config\Services::image()
                ->withFile($uploadPath . $filename)
                ->fit(100, 100, 'center')
                ->save($uploadPath . $thumb, 70);

            /* =========================
               SAVE PATH IN DATABASE
            ========================= */

            $data['photo'] = 'players/' . $userId . '/' . $filename;
            $data['photo_thumb'] = 'players/' . $userId . '/' . $thumb;

            session()->set('photo', $data['photo_thumb']);
        }

        // 🔥 Activate ONLY after successful profile completion
        $userModel->update($userId,[
            ...$data,
            'active' => 1,
            'activation_token' => null
        ]);

        session()->set('profile_completed', 1);

        return redirect()->to('/player/dashboard')
            ->with('success','Profile completed successfully');

    }

    public function autosave()
    {
        $draftModel = new \App\Models\UserProfileDraftModel();

        $userId = session()->get('user_id');

        if(!$userId){
            return $this->response->setJSON(['status'=>false]);
        }

        $data = $this->request->getPost();
        $data['user_id'] = $userId;

        $existing = $draftModel->where('user_id',$userId)->first();

        if($existing){
            $draftModel->update($existing['id'],$data);
        }else{
            $draftModel->insert($data);
        }

        return $this->response->setJSON(['status'=>true]);
    }


}
