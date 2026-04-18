<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\StockModel;
use App\Services\StockService;

class StockController extends BaseController
{

    public function index()
    {

        $productModel = new ProductModel();
        $stockModel = new StockModel();

        $data['products'] = $productModel->findAll();

        return view('stock/index',$data);

    }

    public function receiveStock()
    {

        $data = $this->request->getJSON(true);

        $service = new StockService();

        $service->receiveStock($data['items'],$data['location_id']);

        return $this->response->setJSON([
            'status'=>'success'
        ]);

    }

    public function transferStock()
    {

        $data = $this->request->getJSON(true);

        $service = new StockService();

        $service->transferStock(
            $data['product_id'],
            $data['from_location'],
            $data['to_location'],
            $data['qty']
        );

        return $this->response->setJSON([
            'status'=>'success'
        ]);

    }

    public function transfer()
    {
        $locationModel = new \App\Models\LocationModel();
        $locations = $locationModel->findAll();

        // Get selected location from GET or default to first
        $locationId = $this->request->getGet('location_from') ?? ($locations[0]['id'] ?? 1);

        $productModel = new \App\Models\ProductModel();
        $stockModel = new \App\Models\StockModel();
        $products = $productModel->findAll();
        foreach ($products as &$product) {
            $stock = $stockModel->where(['product_id' => $product['id'], 'location_id' => $locationId])->first();
            $product['qty'] = $stock['quantity'] ?? 0;
        }
        unset($product);

        return view('admin/stock/transfer', [
            'locations' => $locations,
            'products' => $products,
            'selected_location' => $locationId
        ]);
    }

    public function doTransfer()
    {
        $from = $this->request->getPost('location_from');
        $to = $this->request->getPost('location_to');
        $qtys = $this->request->getPost('qty'); // array: product_id => qty
        $userId = session('user_id') ?? 1;

        $stockModel = new \App\Models\StockModel();
        $db = \Config\Database::connect();
        $db->transStart();
        foreach ($qtys as $productId => $qty) {
            $qty = (int)$qty;
            if ($qty > 0 && $from != $to) {
                // Deduct from source
                $row = $stockModel->where(['product_id'=>$productId,'location_id'=>$from])->first();
                if ($row && $row['quantity'] >= $qty) {
                    $stockModel->update($row['id'], ['quantity' => $row['quantity'] - $qty]);
                }
                // Add to destination
                $destRow = $stockModel->where(['product_id'=>$productId,'location_id'=>$to])->first();
                if ($destRow) {
                    $stockModel->update($destRow['id'], ['quantity' => $destRow['quantity'] + $qty]);
                } else {
                    $stockModel->insert(['product_id'=>$productId,'location_id'=>$to,'quantity'=>$qty]);
                }
                // Log movement
                $db->table('stock_movements')->insert([
                    'product_id' => $productId,
                    'location_from' => $from,
                    'location_to' => $to,
                    'qty' => $qty,
                    'movement_type' => 'transfer',
                    'created_at' => date('Y-m-d H:i:s'),
                    'user_id' => $userId
                ]);
            }
        }
        $db->transComplete();
        return redirect()->to(site_url('admin/stock/transfer'))->with('message', 'Stock transferred!');
    }

}