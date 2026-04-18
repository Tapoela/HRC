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

        // ✅ CLEAN FIRST: allow price 0 for specials
        $items = array_filter($items, function($item) {
            return !empty($item['product_id']) &&
                   ($item['qty'] ?? 0) > 0;
        });
        $items = array_values($items);

        // ✅ VALIDATE AFTER CLEAN
        $this->validateStock($items, $saleData['location_id']);

        $db->transStart();

        $saleModel = new SaleModel();
        $saleItemModel = new SaleItemModel();

        // Ensure created_at is set
        if (empty($saleData['created_at'])) {
            $saleData['created_at'] = date('Y-m-d H:i:s');
        }
        $saleId = $saleModel->insert($saleData);

        // --- NEW: Insert a summary special item if any item has special_id ---
        $specialSummary = [];
        foreach ($items as $item) {
            if (!empty($item['special_id'])) {
                $key = $item['special_id'] . '|' . ($item['special_name'] ?? '');
                if (!isset($specialSummary[$key])) {
                    $specialSummary[$key] = [
                        'sale_id' => $saleId,
                        'special_id' => $item['special_id'],
                        'special_name' => $item['special_name'] ?? '',
                        'qty' => 0,
                        'price' => $item['special_price'] ?? 0,
                        'product_id' => null // Not a real product
                    ];
                }
                $specialSummary[$key]['qty'] += $item['special_qty'] ?? $item['qty'];
            }
        }
        foreach ($specialSummary as $summary) {
            $saleItemModel->insert($summary);
        }
        // --- END NEW ---

        foreach ($items as $item)
        {
            $item['sale_id'] = $saleId;
            if (!isset($item['price'])) $item['price'] = 0;
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

        // Only deduct stock if track_stock == 1
        if (!empty($product['track_stock']) && $product['track_stock'] == 1) {
            $this->deductStock($item, $locationId);
        }

        // If you have special logic for 'special' type, keep it here
        if (($product['type'] ?? 'standard') === 'special') {
            $this->createSpecialCredit($item);
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
        foreach ($items as $item) {
            $product = $productModel->find($item['product_id']);
            if (!$product) {
                throw new \Exception("Product not found: " . $item['product_id']);
            }
            // Only check stock if track_stock == 1
            if (!empty($product['track_stock']) && $product['track_stock'] == 1) {
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