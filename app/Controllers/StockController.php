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

}