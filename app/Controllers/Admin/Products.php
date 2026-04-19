<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductTypeModel;


class Products extends BaseController
{
    use DecodesHashId;

    public function index()
    {
        $productModel = new ProductModel();

        $data['products'] = $productModel->findAll();

        return view('admin/products/index', $data);
    }

    public function create()
    {
        $categoryModel = new CategoryModel();
        $typeModel = new ProductTypeModel();

        $data['types'] = $typeModel->findAll();
        $data['categories'] = $categoryModel->findAll();

        return view('admin/products/create',$data);
    }

    public function store()
    {

        $validation = \Config\Services::validation();

        $rules = [

        'name' => 'required|min_length[2]|max_length[100]|is_unique[products.name]',

        'category_id' => 'required|integer',

        'product_type' => 'required',

        'unit_type' => 'required',

        'sell_price' => 'required|decimal',

        'cost_price' => 'required|decimal'

        ];

        //dd($this->request->getPost());

        if(!$this->validate($rules)){

        return redirect()
        ->back()
        ->withInput()
        ->with('errors', $validation->getErrors());

        }

        $productModel = new ProductModel();

        $productModel->insert([
            'name'           => $this->request->getPost('name'),
            'category_id'    => (int)$this->request->getPost('category_id'),
            'product_type'   => $this->request->getPost('product_type'),
            'unit_type'      => $this->request->getPost('unit_type'),
            'unit_size_ml'   => $this->request->getPost('unit_size_ml')   ?: null,
            'serving_size_ml'=> $this->request->getPost('serving_size_ml') ?: null,
            'sell_price'     => $this->request->getPost('sell_price'),
            'cost_price'     => $this->request->getPost('cost_price'),
            'active'         => 1
        ]);

        return redirect()->to('admin/products');

    }

    public function edit($hash)
    {
        $id = $this->decodeHash($hash);

        $productModel  = new ProductModel();
        $categoryModel = new CategoryModel();
        $typeModel     = new ProductTypeModel();

        $product = $productModel->find($id);

        if (!$product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('admin/products/edit', [
            'product'    => $product,
            'categories' => $categoryModel->findAll(),
            'types'      => $typeModel->findAll(),
        ]);
    }

    public function update($hash)
    {
        $id = $this->decodeHash($hash);

        $productModel = new ProductModel();

        $rules = [
            'name'         => "required|min_length[2]|max_length[100]|is_unique[products.name,id,{$id}]",
            'category_id'  => 'required|integer',
            'product_type' => 'required',
            'unit_type'    => 'required',
            'sell_price'   => 'required|decimal',
            'cost_price'   => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', \Config\Services::validation()->getErrors());
        }

        $productModel->update($id, [
            'name'            => $this->request->getPost('name'),
            'category_id'     => (int)$this->request->getPost('category_id'),
            'product_type'    => $this->request->getPost('product_type'),
            'unit_type'       => $this->request->getPost('unit_type'),
            'unit_size_ml'    => $this->request->getPost('unit_size_ml')    ?: null,
            'serving_size_ml' => $this->request->getPost('serving_size_ml') ?: null,
            'sell_price'      => $this->request->getPost('sell_price'),
            'cost_price'      => $this->request->getPost('cost_price'),
            'active'          => $this->request->getPost('active') !== null ? 1 : 0,
        ]);

        return redirect()->to('admin/products')->with('success', 'Product updated successfully.');
    }

}