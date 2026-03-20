<?php 

namespace App\Controllers\Coach;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('coach/dashboard');
    }
}
