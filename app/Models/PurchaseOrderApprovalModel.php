<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderApprovalModel extends Model
{
    protected $table = 'purchase_order_approvals';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'po_id',
        'approver_role',
        'approver_user_id',
        'approver_role_id',
        'signature',
        'approved_at',
        'status',
        'approval_status'
    ];

    protected $useTimestamps = false;
}