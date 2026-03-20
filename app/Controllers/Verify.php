<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Verify extends BaseController
{
    public function index($token)
    {
        $db = \Config\Database::connect();

        $user = $db->table('users')
                   ->where('card_token',$token)
                   ->get()
                   ->getRow();

        if(!$user){
            return view('verify/invalid');
        }

        return view('verify/valid',[
            'user'=>$user
        ]);
    }
}