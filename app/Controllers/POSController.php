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

            // ✅ Step 4 (defaults)
            $data['sale']['payment_type_id'] = $data['sale']['payment_type_id'] ?? null;
            $data['sale']['status'] = $data['sale']['status'] ?? 'paid';

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

            // ✅ THIS is Step 3
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

        $data = $this->request->getJSON(true);

        $service = new SpecialService();

        $service->redeemDrink(

            $data['credit_id'],
            $data['qty']

        );

        return $this->response->setJSON([
            'status'=>'success'
        ]);

    }

    public function openTab()
    {
        $data = $this->request->getJSON(true);

        $tabModel = new \App\Models\TabModel();

        // ✅ Check if tab already exists
        $existing = $tabModel
            ->where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->where('status', 'open')
            ->first();

        if ($existing) {
            return $this->response->setJSON([
                'tab_id' => $existing['id']
            ]);
        }

        // ✅ Otherwise create new tab
        $id = $tabModel->insert([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'location_id' => $data['location_id'],
            'opened_by' => $data['opened_by'],
            'status' => 'open'
        ]);

        return $this->response->setJSON([
            'tab_id' => $id
        ]);
    }

    public function credits()
    {
        $creditModel = new \App\Models\SpecialCreditModel();

        $credits = $creditModel
            ->where('remaining_drinks >',0)
            ->findAll();

        return $this->response->setJSON($credits);
    }

    public function tabTotals()
    {
        $db = \Config\Database::connect();

        $result = $db->query("
            SELECT tab_id, SUM(total) as total
            FROM sales
            WHERE tab_id IS NOT NULL
            AND (status = 'tab' OR payment_type_id IS NULL)
            GROUP BY tab_id
        ")->getResultArray();

        return $this->response->setJSON($result);
    }

    public function payTab()
    {
        $data = $this->request->getJSON(true);

        $db = \Config\Database::connect();

        $db->table('sales')
            ->where('tab_id', $data['tab_id'])
            ->where('status', 'tab')
            ->update([
                'status' => 'paid',
                'payment_type_id' => $data['payment_type_id']
            ]);

        return $this->response->setJSON(['status'=>'success']);
    }

}