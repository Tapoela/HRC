<?php 

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductTypeModel;


class Products extends BaseController
{

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

            'name' => $this->request->getPost('name'),
            'category_id' => (int)$this->request->getPost('category_id'),
            'product_type' => $this->request->getPost('product_type'),
            'unit_type' => $this->request->getPost('unit_type'),
            'sell_price' => $this->request->getPost('sell_price'),
            'cost_price' => $this->request->getPost('cost_price'),
            'active' => 1

        ]);

        return redirect()->to('admin/products');

    }

}