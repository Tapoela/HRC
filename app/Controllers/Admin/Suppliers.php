<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SupplierModel;

class Suppliers extends BaseController
{
    use DecodesHashId;

    public function index()
    {
        $model = new SupplierModel();
        $data['suppliers'] = $model->findAll();
        return view('admin/suppliers/index', $data);
    }

    public function create()
    {
        $model = new SupplierModel();
        $errors = [];
        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'active' => $this->request->getPost('active') ? 1 : 0,
            ];
            if (!$model->insert($data)) {
                $errors = $model->errors();
            } else {
                return redirect()->to('/admin/suppliers');
            }
            // If insert fails, fall through to show form with errors and old input
            return view('admin/suppliers/form', [
                'action' => 'create',
                'supplier' => $data,
                'errors' => $errors
            ]);
        }
        return view('admin/suppliers/form', ['action' => 'create']);
    }

    public function edit($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new SupplierModel();
        $supplier = $model->find($id);
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }
        $errors = [];
        if ($this->request->getMethod(true) === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone' => $this->request->getPost('phone'),
                'email' => $this->request->getPost('email'),
                'active' => $this->request->getPost('active') ? 1 : 0,
            ];
            if (!$model->update($id, $data)) {
                $errors = $model->errors();
            } else {
                return redirect()->to('/admin/suppliers');
            }
            $supplier = array_merge($supplier, $data);
        }
        return view('admin/suppliers/form', ['supplier' => $supplier, 'action' => 'edit', 'errors' => $errors]);
    }

    public function view($hash)
    {
        $id = $this->decodeHash($hash);
        $model = new SupplierModel();
        $supplier = $model->find($id);
        if (!$supplier) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Supplier not found');
        }
        return view('admin/suppliers/view', ['supplier' => $supplier]);
    }
}
