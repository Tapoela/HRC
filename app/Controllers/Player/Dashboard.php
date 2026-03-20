<?php

namespace App\Controllers\Player;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');

        $db = \Config\Database::connect();

        $user = $db->table('users u')
            ->select('
                u.*,
                r.name AS role_name,
                d.name AS division_name,
                p.position_name AS position
            ')
            ->join('roles r', 'r.id = u.role_id', 'left')
            ->join('divisions d', 'd.id = u.division_id', 'left')
            ->join('rugby_positions p', 'p.id = u.position_id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRow();

        // QR verification
        $verifyUrl = base_url('verify/' . $user->id);

        $qr = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($verifyUrl);

        return view('players/dashboard', [
            'user' => $user,
            'qr'   => $qr
        ]);
    }
}
