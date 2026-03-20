<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseAdmin;
use App\Models\SettingsModel;

class Settings extends BaseAdmin
{
    public function index()
    {
        $model = new \App\Models\SettingsModel();

        return view('admin/settings/index', [
            'admin_email'  => $model->getValue('admin_notify_email'),
            'junior_email' => $model->getValue('junior_notify_email'),
            'senior_email' => $model->getValue('senior_notify_email'),
            'coach_email'  => $model->getValue('coach_notify_email'),
        ]);
    }

    public function save()
    {
        $model = new \App\Models\SettingsModel();

        $model->setValue('admin_notify_email',  $this->request->getPost('admin_email'));
        $model->setValue('junior_notify_email', $this->request->getPost('junior_email'));
        $model->setValue('senior_notify_email', $this->request->getPost('senior_email'));
        $model->setValue('coach_notify_email',  $this->request->getPost('coach_email'));

        return redirect()->back()->with('success','Settings Updated');
    }
}