<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\StockModel;

class Stock extends BaseController
{
    public function index()
    {
        return view('admin/stock/dashboard');
    }

    public function receive()
    {
        $productModel = new ProductModel();

        $data['products'] = $productModel
            ->where('active',1)
            ->findAll();

        return view('admin/stock/receive',$data);
    }


    public function saveReceive()
    {
        $data = $this->request->getJSON(true);

        $stockModel = new StockModel();

        foreach($data['items'] as $item)
        {

            if($item['qty'] <= 0) continue;

            $stockModel
                ->where('product_id',$item['product_id'])
                ->where('location_id',1)
                ->increment('quantity',$item['qty']);
        }

        return $this->response->setJSON([
            'status'=>'success'
        ]);
    }

}