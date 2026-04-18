<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PurchaseOrderApprovalModel;

class Dashboard extends BaseController
{
    public function index()
    {

        $userModel = new UserModel();
        $approvalModel = new \App\Models\PurchaseOrderApprovalModel();

        $db = \Config\Database::connect();

        // Pending players (active = 0)
        $pendingPlayers = $userModel
            ->where('active', 0)
            ->orderBy('id','DESC')
            ->findAll(5); // show last 5

        $pendingCount = $userModel
            ->where('active',0)
            ->countAllResults();

        // PO approvals pending for the logged-in user's role
        $pendingPOCount = $approvalModel
            ->where('approver_role_id', session('role_id'))
            ->where('status', 'pending')
            ->countAllResults();

        $user = $db->table('users u')
            ->select('u.*, p.position_name AS position')
            ->join('rugby_positions p', 'p.id = u.position_id', 'left')
            ->where('u.id', session('user_id'))
            ->get()
            ->getRow();

        return view('admin/dashboard',[
            'pendingPlayers' => $pendingPlayers,
            'pendingCount'   => $pendingCount,
            'pendingPOCount' => $pendingPOCount,
            'user'           => $user
        ]);
    }
}
