<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class POSController extends BaseController
{
    public function index()
    {
        return view('admin/pos/index');
    }
}