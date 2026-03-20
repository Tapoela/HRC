<?php

namespace App\Services;

use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SpecialCreditModel;

class SaleService
{

    public function processSale($saleData, $items)
    {
        $db = \Config\Database::connect();

        // ✅ CLEAN FIRST
        $items = array_filter($items, function($item) {
            return !empty($item['product_id']) &&
                   ($item['qty'] ?? 0) > 0 &&
                   ($item['price'] ?? 0) > 0;
        });

        $items = array_values($items);

        // ✅ VALIDATE AFTER CLEAN
        $this->validateStock($items, $saleData['location_id']);

        $db->transStart();

        $saleModel = new SaleModel();
        $saleItemModel = new SaleItemModel();

        $saleId = $saleModel->insert($saleData);

        foreach ($items as $item)
        {
            $item['sale_id'] = $saleId;

            $saleItemModel->insert($item);

            log_message('error', 'ITEM: ' . json_encode($item));

            $this->handleItem($item, $saleData['location_id']);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \Exception("Transaction failed");
        }

        return $saleId;
    }

    private function handleItem($item, $locationId)
    {
        $productModel = new ProductModel();

        $product = $productModel->find($item['product_id']);

        // ✅ Safety check
        if (!$product) {
            throw new \Exception("Product not found: " . $item['product_id']);
        }

        // ✅ Default fallback
        $type = $product['type'] ?? 'standard';

        switch ($type)
        {
            case 'standard':
                $this->deductStock($item, $locationId);
            break;

            case 'special':
                $this->createSpecialCredit($item);
            break;

            default:
                throw new \Exception("Invalid product type: " . $type);
        }
    }

    private function deductStock($item,$locationId)
    {

        $stockModel = new StockModel();

        $stock = $stockModel
            ->where('product_id', $item['product_id'])
            ->where('location_id', $locationId)
            ->first();

        if ($stock['quantity'] < $item['qty']) {
            throw new \Exception("Stock changed during sale.");
        }

        $stockModel
            ->where('id', $stock['id'])
            ->set('quantity', 'quantity - '.$item['qty'], false)
            ->update();

    }

    private function createSpecialCredit($item)
    {

        $specialCreditModel = new SpecialCreditModel();

        $specialCreditModel->insert([
            'sale_id'=>$item['sale_id'],
            'special_id'=>$item['product_id'],
            'total_drinks'=>4,
            'remaining_drinks'=>4
        ]);

    }

    private function validateStock($items, $locationId)
    {
        $stockModel = new StockModel();
        $productModel = new ProductModel();

        foreach ($items as $item)
        {
            $product = $productModel->find($item['product_id']);

            if (!$product) {
                throw new \Exception("Invalid product ID: " . $item['product_id']);
            }

            $type = $product['type'] ?? 'standard';

            // ✅ Only check stock for standard products
            if ($type === 'standard')
            {
                $stock = $stockModel
                    ->where('product_id', $item['product_id'])
                    ->where('location_id', $locationId)
                    ->first();

                if (!$stock) {
                    throw new \Exception("No stock for product: " . $product['name']);
                }

                if ($stock['quantity'] < $item['qty']) {
                    throw new \Exception("Not enough stock for: " . $product['name']);
                }
            }
        }
    }

}