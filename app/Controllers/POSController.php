<?php

namespace App\Controllers;

use App\Services\SaleService;
use App\Services\SpecialService;
use App\Services\TabService;
use App\Models\ProductModel;
use App\Models\TabModel;

class POSController extends BaseController
{

    public function init()
    {

        $productModel = new \App\Models\ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $tabModel = new \App\Models\TabModel();

        return $this->response->setJSON([

            'products' => $productModel
                ->where('active',1)
                ->findAll(),

            'categories' => $categoryModel
                ->where('active',1)
                ->findAll(),

            'tabs' => $tabModel
                ->where('status','open')
                ->findAll()

        ]);

    }

    public function createSale()
    {
        try {

            $data = $this->request->getJSON(true);

            // ✅ Validate data
            if (empty($data['sale']) || empty($data['items'])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Sale and items are required'
                    ]);
            }

            // ✅ Step 4 (defaults)
            $data['sale']['payment_type_id'] = $data['sale']['payment_type_id'] ?? null;
            // Set status to 'tab' if tab_id is present, otherwise 'paid'
            if (!empty($data['sale']['tab_id'])) {
                $data['sale']['status'] = 'tab';
            } else {
                $data['sale']['status'] = $data['sale']['status'] ?? 'paid';
            }

            $service = new SaleService();

            $saleId = $service->processSale(
                $data['sale'],
                $data['items']
            );

            return $this->response->setJSON([
                'status' => 'success',
                'sale_id' => $saleId
            ]);

        } catch (\Exception $e) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
        }
    }

    public function redeemCredit()
    {
        try {

            $data = $this->request->getJSON(true);

            if (empty($data['credit_id']) || empty($data['qty'])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Credit ID and quantity are required'
                    ]);
            }

            $service = new SpecialService();

            $service->redeemDrink(
                $data['credit_id'],
                $data['qty']
            );

            return $this->response->setJSON([
                'status'=>'success'
            ]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to redeem drink: ' . $e->getMessage()
                ]);
        }
    }

    public function openTab()
    {
        try {
            $data = $this->request->getJSON(true);

            // ✅ Validate required fields
            if (empty($data['name']) || empty($data['phone'])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Name and phone are required'
                    ]);
            }

            $tabModel = new \App\Models\TabModel();

            // ✅ Check if tab already exists
            $existing = $tabModel
                ->where('name', $data['name'])
                ->where('phone', $data['phone'])
                ->where('status', 'open')
                ->first();

            if ($existing) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'tab_id' => $existing['id']
                ]);
            }

            // ✅ Otherwise create new tab - use insertID()
            $tabModel->insert([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'location_id' => $data['location_id'] ?? 1,
                'opened_by' => $data['opened_by'] ?? 1,
                'status' => 'open'
            ]);

            $id = $tabModel->insertID();

            return $this->response->setJSON([
                'status' => 'success',
                'tab_id' => $id
            ]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to open tab: ' . $e->getMessage()
                ]);
        }
    }

    public function credits()
    {
        try {
            $creditModel = new \App\Models\SpecialCreditModel();

            $credits = $creditModel
                ->where('remaining_drinks >', 0)
                ->findAll();

            return $this->response->setJSON($credits);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to fetch credits: ' . $e->getMessage()
                ]);
        }
    }

    public function tabTotals()
    {
        try {
            $db = \Config\Database::connect();

            // ✅ FIXED: Use QueryBuilder instead of raw query
            $result = $db->table('sales')
                ->select('tab_id, SUM(total) as total')
                ->where('tab_id IS NOT NULL', null, false)
                ->where('status', 'tab')
                ->groupBy('tab_id')
                ->get()
                ->getResultArray();

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to fetch tab totals: ' . $e->getMessage()
                ]);
        }
    }

    public function payTab()
    {
        try {
            $data = $this->request->getJSON(true);

            if (empty($data['tab_id'])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Tab ID is required'
                    ]);
            }

            $db = \Config\Database::connect();

            $updateData = [
                'status' => 'paid',
                'payment_type_id' => $data['payment_type_id'] ?? 1
            ];
            if (!empty($data['card_reference'])) {
                $updateData['card_reference'] = $data['card_reference'];
            }

            $db->table('sales')
                ->where('tab_id', $data['tab_id'])
                ->where('status', 'tab')
                ->update($updateData);

            return $this->response->setJSON(['status'=>'success']);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to pay tab: ' . $e->getMessage()
                ]);
        }
    }

    public function closeTab()
    {
        try {
            $data = $this->request->getJSON(true);

            if (empty($data['tab_id'])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => 'error',
                        'message' => 'Tab ID is required'
                    ]);
            }

            $tabModel = new \App\Models\TabModel();

            $tabModel->update($data['tab_id'], [
                'status' => 'closed',
                'closed_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Tab closed successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to close tab: ' . $e->getMessage()
                ]);
        }
    }

    public function addCredit()
    {
        try {
            $data = $this->request->getJSON(true);
            if (empty($data['name']) || empty($data['phone']) || empty($data['qty'])) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Name, phone, and quantity are required'
                ]);
            }
            $model = new \App\Models\DrinkCreditModel();
            // Check if credit exists for this person
            $credit = $model->where('name', $data['name'])->where('phone', $data['phone'])->first();
            if ($credit) {
                $model->update($credit['id'], [
                    'total_bought' => $credit['total_bought'] + (int)$data['qty']
                ]);
            } else {
                $model->insert([
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'total_bought' => (int)$data['qty'],
                    'total_redeemed' => 0
                ]);
            }
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function listCredits()
    {
        $model = new \App\Models\DrinkCreditModel();
        $credits = $model->findAll();
        foreach ($credits as &$c) {
            $c['remaining'] = $c['total_bought'] - $c['total_redeemed'];
        }
        return $this->response->setJSON($credits);
    }

    public function redeemCreditDrinks()
    {
        try {
            $data = $this->request->getJSON(true);
            if (empty($data['id']) || empty($data['qty'])) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Credit ID and quantity are required'
                ]);
            }
            $model = new \App\Models\DrinkCreditModel();
            $credit = $model->find($data['id']);
            if (!$credit) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Credit not found'
                ]);
            }
            $remaining = $credit['total_bought'] - $credit['total_redeemed'];
            if ($remaining < (int)$data['qty']) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'Not enough credits remaining'
                ]);
            }
            $model->update($data['id'], [
                'total_redeemed' => $credit['total_redeemed'] + (int)$data['qty']
            ]);
            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function locations()
    {
        $model = new \App\Models\LocationModel();
        $locations = $model->findAll();
        return $this->response->setJSON($locations);
    }

    public function addLocation()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['name'])) {
            return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Name required']);
        }
        $model = new \App\Models\LocationModel();
        if ($model->where('name', $data['name'])->first()) {
            return $this->response->setStatusCode(400)->setJSON(['status'=>'error','message'=>'Location exists']);
        }
        $id = $model->insert(['name'=>$data['name']]);
        return $this->response->setJSON(['status'=>'success','id'=>$id]);
    }

    // API: List all active specials
    public function apiSpecials()
    {
        $specialModel = new \App\Models\SpecialModel();
        $specials = $specialModel->where('active', 1)->findAll();
        return $this->response->setJSON($specials);
    }

    // API: Get special items/slots and available products for each slot
    public function apiSpecialItems($specialId)
    {
        $specialItemModel = new \App\Models\SpecialItemModel();
        $productModel = new \App\Models\ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $db = \Config\Database::connect();
        $items = $specialItemModel->where('special_id', $specialId)->findAll();
        $result = [];
        foreach ($items as $item) {
            // Get category info
            $category = $categoryModel->find($item['category_id']);
            // Get all active products in this category
            $products = $productModel
                ->where('category_id', $item['category_id'])
                ->where('active', 1)
                ->findAll();
            // For each product, get stock from stock_levels table (in servings)
            foreach ($products as &$prod) {
                $stockRow = $db->table('stock_levels')
                    ->selectSum('quantity')
                    ->where('product_id', $prod['id'])
                    ->get()
                    ->getRow();
                $totalMl = 0;
                // If unit_type is 'bottle' or 'can', quantity is in units, so multiply by unit_size_ml
                if (isset($prod['unit_type']) && ($prod['unit_type'] === 'bottle' || $prod['unit_type'] === 'can')) {
                    $unitSize = isset($prod['unit_size_ml']) ? (int)$prod['unit_size_ml'] : 0;
                    $totalMl = $stockRow && $stockRow->quantity !== null ? ((float)$stockRow->quantity) * $unitSize : 0;
                } else {
                    // Otherwise, treat quantity as ml
                    $totalMl = $stockRow && $stockRow->quantity !== null ? (float)$stockRow->quantity : 0;
                }
                $servingSize = isset($prod['serving_size_ml']) && $prod['serving_size_ml'] ? (int)$prod['serving_size_ml'] : 1;
                $prod['stock'] = $servingSize > 0 ? floor($totalMl / $servingSize) : 0;
                $prod['serving_size_ml'] = $servingSize;
                $prod['unit_type'] = $prod['unit_type'] ?? null;
                $prod['unit_size_ml'] = isset($prod['unit_size_ml']) ? (int)$prod['unit_size_ml'] : null;
            }
            // Debug: log products and stock calculation
            log_message('debug', 'Special slot category_id: ' . $item['category_id'] . ' products: ' . json_encode($products));
            $result[] = [
                'slot_id' => $item['id'],
                'category_id' => $item['category_id'],
                'category_name' => $category ? $category['name'] : '',
                'quantity' => isset($item['qty']) ? $item['qty'] : 1, // Use 'qty' field, fallback to 1
                'products' => $products
            ];
        }
        // Debug: log final result
        log_message('debug', 'Special items API result: ' . json_encode($result));
        return $this->response->setJSON($result);
    }

    // API: Sell a special (check/deduct stock, record sale)
    public function sellSpecial()
    {
        $db = \Config\Database::connect();
        $specialId = $this->request->getPost('special_id');
        $selectedProducts = $this->request->getPost('selected_products'); // array: [slot_id => [product_id, ...]]
        $quantities = $this->request->getPost('quantities'); // array: [slot_id => qty]

        // Fetch special items (slots)
        $specialItemModel = new \App\Models\SpecialItemModel();
        $slots = $specialItemModel->where('special_id', $specialId)->findAll();

        $productModel = new \App\Models\ProductModel();
        $categoryModel = new \App\Models\CategoryModel();
        $errors = [];
        $stockUpdates = [];

        foreach ($slots as $slot) {
            $slotId = $slot['id'];
            $category = $categoryModel->find($slot['category_id']);
            $categoryName = $category ? $category['name'] : '';
            // Always treat qty as number of servings (shots/glasses), not ml!
            $qty = isset($quantities[$slotId]) ? (int)$quantities[$slotId] : (isset($slot['qty']) ? (int)$slot['qty'] : 1);
            $productsInSlot = $selectedProducts[$slotId] ?? [];
            if (!is_array($productsInSlot)) {
                $productsInSlot = [$productsInSlot];
            }
            if (empty($productsInSlot)) {
                $errors[] = "No product selected for slot $slotId";
                continue;
            }
            foreach ($productsInSlot as $productId) {
                $product = $productModel->find($productId);
                if (!$product) {
                    $errors[] = "Product $productId not found";
                    continue;
                }
                // Each serving deducts serving_size_ml from stock
                $servingSize = isset($product['serving_size_ml']) && $product['serving_size_ml'] ? (int)$product['serving_size_ml'] : 1;
                $totalMlToDeduct = $qty * $servingSize;
                // Check available stock (in ml)
                $stockRow = $db->table('stock_levels')
                    ->selectSum('quantity')
                    ->where('product_id', $productId)
                    ->get()
                    ->getRow();
                $totalMl = 0;
                if (isset($product['unit_type']) && ($product['unit_type'] === 'bottle' || $product['unit_type'] === 'can')) {
                    $unitSize = isset($product['unit_size_ml']) ? (int)$product['unit_size_ml'] : 0;
                    $totalMl = $stockRow && $stockRow->quantity !== null ? ((float)$stockRow->quantity) * $unitSize : 0;
                } else {
                    $totalMl = $stockRow && $stockRow->quantity !== null ? (float)$stockRow->quantity : 0;
                }
                $availableServings = $servingSize > 0 ? floor($totalMl / $servingSize) : 0;
                // Log BEFORE deduction
                log_message('debug', "[Special Sale] CATEGORY: $categoryName | SLOT: $slotId | PRODUCT: {$product['name']} (ID: $productId) | Stock BEFORE: {$totalMl}ml ({$availableServings} servings) | Qty to sell: $qty | Serving size: {$servingSize}ml | Total to deduct: {$totalMlToDeduct}ml");
                if ($availableServings < $qty) {
                    $errors[] = "Not enough stock for product {$product['name']} (requested $qty servings, available $availableServings)";
                    continue;
                }
                // Deduct from stock_levels (in ml)
                $stockUpdates[] = [
                    'product_id' => $productId,
                    'quantity' => -$totalMlToDeduct,
                    'note' => 'Sold via special',
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                // Log AFTER deduction (simulate)
                $afterMl = $totalMl - $totalMlToDeduct;
                $afterServings = $servingSize > 0 ? floor($afterMl / $servingSize) : 0;
                log_message('debug', "[Special Sale] CATEGORY: $categoryName | SLOT: $slotId | PRODUCT: {$product['name']} (ID: $productId) | Stock AFTER: {$afterMl}ml ({$afterServings} servings)");
            }
        }

        if ($errors) {
            log_message('debug', '[Special Sale] Errors: ' . json_encode($errors));
            return $this->response->setStatusCode(400)->setJSON(['errors' => $errors]);
        }

        // Insert stock deductions
        if ($stockUpdates) {
            $db->table('stock_levels')->insertBatch($stockUpdates);
        }

        // Optionally: log sale, update reports, etc.
        log_message('debug', '[Special Sale] Stock updates: ' . json_encode($stockUpdates));
        return $this->response->setJSON(['success' => true]);
    }

}