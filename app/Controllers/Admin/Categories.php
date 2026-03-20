<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{

    public function index()
    {
        $CategoryModel = new CategoryModel();

        $data['categories'] = $CategoryModel->findAll();

        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/create');
    }

    public function store()
    {

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name]'
        ];

        if(!$this->validate($rules)){
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $CategoryModel = new CategoryModel();

        $CategoryModel->insert([
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('admin/categories');
    }

}