<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class Activate extends BaseController
{
   public function index($token = null)
    {

        //dd('ACTIVATE HIT');

        $userModel = new \App\Models\UserModel();

        $user = $userModel
            ->select('users.*, roles.name as role_name')
            ->join('roles','roles.id = users.role_id','left')
            ->where('activation_token',$token)
            ->first();

        //dd($user);

        if(!$user){
            return redirect()->to('/login')
                ->with('error','Invalid activation link.');
        }

        if($user['active'] == 1 && $user['profile_completed'] == 1){
            return redirect()->to('/login')
                ->with('success','Account already activated.');
        }

        // Activate account
        $userModel->update($user['id'],[
            'active' => 0,
            'activated_at' => date('Y-m-d H:i:s'),
            'activation_token' => null,
            'activation_expires' => null
        ]);

        $session = session();

        session()->set([
            'user_id'   => $user['id'],
            'name'      => $user['name'],
            'role_id'   => $user['role_id'],
            'role'      => $user['role_name'],
            'logged_in' => true
        ]);

        $session->regenerate(); // ✔ works because session is active

        // 🔥 REDIRECT WITHOUT TOKEN
        return redirect()->to('/player/profile/setup');
    }

}
