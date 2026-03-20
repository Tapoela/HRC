<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Controllers\Admin\BaseAdmin;

use Endroid\QrCode\Builder\Builder;

class Dashboard extends BaseAdmin

{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect()->to('/login');
        }

        // 👇 NEW FIRST LOGIN CHECK
        if(session('role') === 'player' && !session('profile_completed')){
            return redirect()->to('/player/profile/setup');
        }

        switch(session('role')){
            case 'admin': return redirect()->to('/admin/dashboard');
            case 'coach': return redirect()->to('/coach/dashboard');
            case 'player': return redirect()->to('/player/dashboard');
            case 'chairman':
            case 'treasurer':
            case 'vice chairman':

            return redirect()->to('/admin/dashboard');
        }


        return redirect()->to('/dashboard-default');
    }

    public function default()
    {
        return view('dashboard_default');
    }

    public function card()
    {
        $userId = session()->get('user_id');

        $user = $this->db->table('users')
                         ->where('id',$userId)
                         ->get()
                         ->getRow();

        $verifyUrl = base_url('verify/'.$user->card_token);

        $qr = Builder::create()
            ->data($verifyUrl)
            ->size(300)
            ->margin(10)
            ->build();

        $qrImage = base64_encode($qr->getString());

        return view('player/card',[
            'user'=>$user,
            'qr'=>$qrImage
        ]);
    }

}
