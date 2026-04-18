<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpecialModel;
use App\Models\SpecialItemModel;
use App\Models\ProductModel;

class Specials extends BaseController
{
    use DecodesHashId;

    public function index()
    {
        $specialModel = new SpecialModel();
        $specials = $specialModel->findAll();
        return view('admin/specials/index', ['specials' => $specials]);
    }

    public function create()
    {
        $productModel = new ProductModel();
        $products = $productModel->findAll();
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->findAll();
        return view('admin/specials/form', [
            'products' => $products,
            'categories' => $categories,
            'special' => null,
            'items' => []
        ]);
    }

    public function edit($hash)
    {
        $id = $this->decodeHash($hash);
        $specialModel = new SpecialModel();
        $specialItemModel = new SpecialItemModel();
        $productModel = new ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $special = $specialModel->find($id);
        $items = $specialItemModel->where('special_id', $id)->findAll();
        $products = $productModel->findAll();
        $categories = $categoryModel->findAll();
        return view('admin/specials/form', [
            'special' => $special,
            'items' => $items,
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function update($hash)
    {
        $id = $this->decodeHash($hash);
        $specialModel = new SpecialModel();
        $specialItemModel = new SpecialItemModel();
        $data = $this->request->getPost();
        $specialModel->update($id, [
            'name' => $data['name'],
            'price' => $data['price'],
            'active' => $data['active'] ?? 1
        ]);
        // Remove old items
        $specialItemModel->where('special_id', $id)->delete();
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['category_id']) && !empty($item['quantity'])) {
                    $specialItemModel->insert([
                        'special_id' => $id,
                        'category_id' => $item['category_id'],
                        'qty' => $item['quantity'],
                    ]);
                }
            }
        }
        return redirect()->to('/admin/stock/specials')->with('success', 'Special updated.');
    }

    public function store()
    {
        $specialModel = new SpecialModel();
        $specialItemModel = new SpecialItemModel();
        $data = $this->request->getPost();
        $specialModel->save([
            'id' => $data['id'] ?? null,
            'name' => $data['name'],
            'price' => $data['price'],
            'active' => $data['active'] ?? 1
        ]);
        $specialId = $data['id'] ?? $specialModel->getInsertID();
        // Remove old items if editing
        $specialItemModel->where('special_id', $specialId)->delete();
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!empty($item['category_id']) && !empty($item['quantity'])) {
                    $specialItemModel->insert([
                        'special_id' => $specialId,
                        'category_id' => $item['category_id'],
                        'qty' => $item['quantity'],
                    ]);
                }
            }
        }
        return redirect()->to('/admin/stock/specials')->with('success', 'Special saved.');
    }

    public function delete($hash)
    {
        $id = $this->decodeHash($hash);
        $specialModel = new SpecialModel();
        $specialItemModel = new SpecialItemModel();
        $specialModel->delete($id);
        $specialItemModel->where('special_id', $id)->delete();
        return redirect()->to('/admin/stock/specials')->with('success', 'Special deleted.');
    }
}
