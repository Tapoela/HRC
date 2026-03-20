<?php

namespace App\Services;

use App\Models\StockModel;

class StockService
{

    public function receiveStock($items,$locationId)
    {

        $stockModel = new StockModel();

        foreach($items as $item)
        {

            $stockModel
            ->where('product_id',$item['product_id'])
            ->where('location_id',$locationId)
            ->increment('quantity',$item['qty']);

        }

    }

    public function transferStock($productId,$from,$to,$qty)
    {

        $stockModel = new StockModel();

        $stockModel
        ->where('product_id',$productId)
        ->where('location_id',$from)
        ->decrement('quantity',$qty);

        $stockModel
        ->where('product_id',$productId)
        ->where('location_id',$to)
        ->increment('quantity',$qty);

    }

}