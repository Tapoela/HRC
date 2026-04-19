<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderItemModel;
use App\Models\PurchaseOrderApprovalModel;

class PurchaseOrders extends BaseController
{
	use DecodesHashId;
	protected $db;

	public function __construct()
	{
	    $this->db = \Config\Database::connect();
	}

    public function index()
	{
	    $poModel = new PurchaseOrderModel();
	    $approvalModel = new PurchaseOrderApprovalModel();

	    $orders = $poModel
	        ->select('purchase_orders.*, suppliers.name AS supplier_name')
	        ->join('suppliers','suppliers.id = purchase_orders.supplier_id','left')
	        ->orderBy('purchase_orders.id','DESC')
	        ->findAll();

	    foreach ($orders as &$order) {

		    $nextApproval = $approvalModel
			    ->where('po_id', $order['id'])
			    ->where('status', 'pending')
			    ->orderBy('id', 'ASC')
			    ->first();

			if($nextApproval){
			    $order['next_approver_role_id'] = $nextApproval['approver_role_id'];
			} else {
			    $order['next_approver_role_id'] = null;
			}
		}

	    $data['orders'] = $orders;

	    //dd($data);

	    return view('admin/stock/po_list', $data);
	}

    public function create()
	{
	    $productModel = new \App\Models\ProductModel();
	    $supplierModel = new \App\Models\SupplierModel();

	    $data['products'] = $productModel
	        ->where('active',1)
	        ->findAll();

	    $data['suppliers'] = $supplierModel->findAll();

	    return view('admin/stock/po_create', $data);
	}

    public function store()
	{
	    $data = $this->request->getJSON(true);

	    if(empty($data['items'])){
	        return $this->response->setJSON([
	            'status' => 'error',
	            'message' => 'No items selected'
	        ]);
	    }

	    $poModel = new PurchaseOrderModel();
	    $itemModel = new PurchaseOrderItemModel();

	    // Generate PO Number
	    $year = date('Y');

	    $lastPO = $poModel
	        ->like('po_number', "PO-$year", 'after')
	        ->orderBy('id','DESC')
	        ->first();

	    $nextNumber = 1;

	    if($lastPO){
	        $parts = explode('-', $lastPO['po_number']);
	        $nextNumber = intval(end($parts)) + 1;
	    }

	    $poNumber = 'PO-' . $year . '-' . str_pad($nextNumber,4,'0',STR_PAD_LEFT);

	    // Create PO
	    $poId = $poModel->insert([
		    'po_number'      => $poNumber,
		    'supplier_id'    => $data['supplier_id'],
		    'order_date'     => date('Y-m-d'),
		    'status'         => 'pending',
		    'approval_status'=> 'pending', // 🔥 ADD THIS
		    'created_by'     => session('user_id')
		]);

	    if(!$poId){
	        return $this->response->setJSON([
	            'status'=>'error',
	            'message'=>'Failed to create PO'
	        ]);
	    }

	    $total = 0;

	    foreach($data['items'] as $item){

	        $qty = (int)$item['qty'];
	        $price = (float)$item['price'];

	        $lineTotal = $qty * $price;

	        $total += $lineTotal;

	        $itemModel->insert([
	            'po_id' => $poId,
	            'product_id' => $item['product_id'],
	            'qty_ordered' => $qty,
	            'cost_price' => $price
	        ]);

	    }

	    $approvalModel = new PurchaseOrderApprovalModel();

		$workflow = $this->db->table('approval_workflow')
		    ->where('active',1)
		    ->orderBy('step_order','ASC')
		    ->get()
		    ->getResultArray();

		$rows = [];

		foreach($workflow as $step){

		    $rows[] = [
		        'po_id'=>$poId,
		        'approver_role_id'=>$step['role_id'],
		        'status'=>'pending'
		    ];
		}

		if (!empty($rows)) {
		    $approvalModel->insertBatch($rows);
		}

	    // Update total
	    $poModel->update($poId,[
	        'total_amount'=>$total
	    ]);

	    return $this->response->setJSON([
	        'status'=>'success',
	        'po_number'=>$poNumber
	    ]);
	}

	public function view($hash)
	{
	    $id = $this->decodeHash($hash);
	    $poModel = new PurchaseOrderModel();
	    $itemModel = new PurchaseOrderItemModel();
	    $approvalModel = new PurchaseOrderApprovalModel();

	    $data['po'] = $poModel->find($id);

	    $data['items'] = $itemModel
	        ->select('purchase_order_items.*, products.name')
	        ->join('products','products.id = purchase_order_items.product_id')
	        ->where('po_id',$id)
	        ->findAll();

	    $data['approvals'] = $approvalModel
		    ->select('purchase_order_approvals.*, roles.name as role_name')
		    ->join('roles','roles.id = purchase_order_approvals.approver_role_id','left')
		    ->where('po_id',$id)
		    ->orderBy('id','ASC')
		    ->findAll();

	    return view('admin/stock/po_view',$data);
	}

	public function edit($hash)
	{
	    $id = $this->decodeHash($hash);
	    $poModel = new PurchaseOrderModel();
	    $itemModel = new PurchaseOrderItemModel();
	    $productModel = new ProductModel();
	    $supplierModel = new \App\Models\SupplierModel();

	    $data['po'] = $poModel->find($id);

	    $data['products'] = $productModel
	        ->where('active',1)
	        ->findAll();

	    $data['suppliers'] = $supplierModel->findAll();

	    $data['items'] = $itemModel
	        ->where('po_id',$id)
	        ->findAll();

	    $data['products'] = $productModel
	        ->where('active',1)
	        ->findAll();

	    return view('admin/stock/po_edit',$data);
	}

	public function pdf($hash)
	{
	    $id = $this->decodeHash($hash);
	    $poModel = new PurchaseOrderModel();
	    $itemModel = new PurchaseOrderItemModel();
	    $supplierModel = new SupplierModel();
	    $approvalModel = new PurchaseOrderApprovalModel();

	    $po = $poModel->find($id);

	    $supplier = $supplierModel->find($po['supplier_id']);

	    $items = $itemModel
	        ->select('purchase_order_items.*, products.name')
	        ->join('products','products.id = purchase_order_items.product_id')
	        ->where('po_id',$id)
	        ->findAll();

	    $approvals = $approvalModel
		    ->select('purchase_order_approvals.*, roles.name as role_name')
		    ->join('roles','roles.id = purchase_order_approvals.approver_role_id','left')
		    ->where('po_id',$id)
		    ->orderBy('id','ASC')
		    ->findAll();

	    $path = FCPATH . 'assets/Logos/BergeLogo.png';
	    $type = pathinfo($path, PATHINFO_EXTENSION);
	    $img = file_get_contents($path);
	    $logo = 'data:image/'.$type.';base64,'.base64_encode($img);

	    $data = [
	        'po'=>$po,
	        'items'=>$items,
	        'supplier'=>$supplier,
	        'logo'=>$logo,
	        'approvals'=>$approvals
	    ];

	    $html = view('admin/stock/po_pdf',$data);

	    $dompdf = new \Dompdf\Dompdf();

	    $dompdf->loadHtml($html);
	    $dompdf->setPaper('A4','portrait');
	    $dompdf->render();

	    $dompdf->stream("PO_".$po['po_number'].".pdf",["Attachment"=>false]);
	}

	public function update($hash)
	{
	    $id = $this->decodeHash($hash);
	    $data = $this->request->getJSON(true);

	    $poModel = new PurchaseOrderModel();
	    $itemModel = new PurchaseOrderItemModel();

	    if(empty($data['items'])){
	        return $this->response->setJSON([
	            'status'=>'error',
	            'message'=>'No items'
	        ]);
	    }

	    $po = $poModel->find($id);

	    if(!$po){
	        return $this->response->setJSON([
	            'status'=>'error',
	            'message'=>'PO not found'
	        ]);
	    }

	    $total = 0;

	    $itemModel->where('po_id',$id)->delete();

	    foreach($data['items'] as $item){

	        $qty = (int)$item['qty'];
	        $price = (float)$item['price'];

	        $lineTotal = $qty * $price;

	        $total += $lineTotal;

	        $itemModel->insert([
	            'po_id'=>$id,
	            'product_id'=>$item['product_id'],
	            'qty_ordered'=>$qty,
	            'cost_price'=>$price
	        ]);
	    }

	    $poModel->update($id,[
	        'supplier_id'=>$data['supplier_id'],
	        'total_amount'=>$total
	    ]);

	    return $this->response->setJSON([
	        'status'=>'success',
	        'po_number'=>$po['po_number']
	    ]);
	}

	public function approve($hash)
	{
		$id = $this->decodeHash($hash);
		$poModel = new PurchaseOrderModel();
		$approvalModel = new PurchaseOrderApprovalModel();
		//dd($approvalModel->findAll());

		$data['po'] = $poModel->find($id);

		$data['approvals'] = $approvalModel
		    ->select('purchase_order_approvals.*, roles.name as role_name')
		    ->join('roles','roles.id = purchase_order_approvals.approver_role_id','left')
		    ->where('po_id',$id)
		    ->orderBy('id','ASC')
		    ->findAll();

		return view('admin/stock/po_approve',$data);
	}

		public function saveApproval()
	{
	    $data = $this->request->getJSON(true);

	    $approvalModel = new PurchaseOrderApprovalModel();
	    $poModel = new PurchaseOrderModel();

	    $userRoleId = session('role_id');

	    // Decode the hashed po_id
	    $poId = $this->decodeHash($data['po_id'] ?? '');
	    $data['po_id'] = $poId;
	    $approval = $approvalModel
	        ->where('po_id',$data['po_id'])
	        ->where('status','pending')
	        ->orderBy('id','ASC')
	        ->first();

	    // If no pending approval row exists, admin (role_id=1) can approve directly
	    if(!$approval){
	        if($userRoleId != 1){
	            return $this->response->setJSON([
	                'status'=>'error',
	                'message'=>'No pending approval step found'
	            ]);
	        }
	        // Admin direct approval — insert a completed approval row
	        $approvalModel->insert([
	            'po_id'            => $data['po_id'],
	            'approver_role_id' => $userRoleId,
	            'approver_user_id' => session('user_id'),
	            'signature'        => $data['signature'],
	            'approved_at'      => date('Y-m-d H:i:s'),
	            'status'           => 'approved',
	            'approval_status'  => 'approved',
	        ]);
	        $poModel->update($data['po_id'], ['approval_status' => 'approved']);
	        return $this->response->setJSON(['status' => 'completed']);
	    }

	    // ✅ role validation — admin can approve any step
	    if($approval['approver_role_id'] != $userRoleId && $userRoleId != 1){
	        return $this->response->setJSON([
	            'status'=>'error',
	            'message'=>'You are not allowed to approve this step'
	        ]);
	    }

	    // save approval
	    $approvalModel->update($approval['id'],[
	        'approver_user_id'=>session('user_id'),
	        'signature'=>$data['signature'],
	        'approved_at'=>date('Y-m-d H:i:s'),
	        'status'=>'approved'
	    ]);

	    // check remaining
	    $remaining = $approvalModel
	        ->where('po_id',$data['po_id'])
	        ->where('status','pending')
	        ->countAllResults();

	    if($remaining == 0){

	        $poModel->update($data['po_id'],[
	            'approval_status'=>'approved'
	        ]);

	        return $this->response->setJSON(['status'=>'completed']);

	    }else{

	        $poModel->update($data['po_id'],[
	            'approval_status'=>'partially_approved'
	        ]);

	        return $this->response->setJSON(['status'=>'success']);
	    }
	}

	public function receive($hash)
	{
	    try {
	        $poId = $this->decodeHash($hash);
	        $poModel = new \App\Models\PurchaseOrderModel();
	        $itemsModel = new \App\Models\PurchaseOrderItemModel();

	        $po = $poModel->find($poId);

	        // Ensure PO exists
	        if (!$po) {
	            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	        }

	        // Safely read approval_status (may be null if DB column missing)
	        $approvalStatus = isset($po['approval_status']) ? strtolower($po['approval_status']) : null;

	        if ($approvalStatus !== 'approved') {
	            return redirect()->back()->with('error','PO not approved yet');
	        }

	        $data['po'] = $po;
	        $data['items'] = $itemsModel
	            ->select('purchase_order_items.*, products.name as product_name, products.unit_type as product_unit_type, products.unit_size_ml as product_unit_size_ml, products.serving_size_ml as product_serving_size_ml')
	            ->join('products', 'products.id = purchase_order_items.product_id', 'left')
	            ->where('purchase_order_items.po_id', $poId)
	            ->findAll();

	        return view('admin/stock/receive', $data);
	
	    } catch (\Throwable $e) {
	        // Log full exception for diagnostics
	        log_message('error', 'PurchaseOrders::receive error for hash [' . ($hash ?? '') . ']: ' . $e->getMessage());
	        log_message('error', $e->getTraceAsString());

	        // Redirect back to PO list with a generic error
	        return redirect()->to(site_url('admin/purchaseorders'))->with('error', 'Unable to open Receive page. See logs for details.');
	    }
	}

	public function processReceive()
	{
	    $poId = $this->decodeHash($this->request->getPost('po_id') ?? '');
	    $receiveQty = $this->request->getPost('receive_qty');
		$unit_type = $this->request->getPost('unit_type');
		$unit_size_ml = $this->request->getPost('unit_size_ml');
		$serving_size_ml = $this->request->getPost('serving_size_ml');

	    $poModel = new \App\Models\PurchaseOrderModel();
	    $poItemsModel = new \App\Models\PurchaseOrderItemModel();
	    $stockModel = new \App\Models\StockModel();
		$productModel = new \App\Models\ProductModel();

		if(!is_array($receiveQty)){
		    return redirect()->back()->with('error','No quantities submitted');
		}

	    $po = $poModel->find($poId);

		if($po['approval_status'] !== 'approved'){
		    return redirect()->back()->with('error','PO not approved');
		}

	    foreach ($receiveQty as $itemId => $qty) {
	        $qty = (int)$qty;
	        if ($qty <= 0) continue;

	        // Get product_id for this item
	        $item = $poItemsModel->find($itemId);
	        $product_id = $item['product_id'];

	        $unitType = $unit_type[$itemId] ?? 'bottle';
	        $unitSize = (int)($unit_size_ml[$itemId] ?? 0);
	        $servingSize = (int)($serving_size_ml[$itemId] ?? 0);

	        // Update product master record with these values
	        $productModel->update($product_id, [
	            'unit_type' => $unitType,
	            'unit_size_ml' => $unitSize,
	            'serving_size_ml' => $servingSize
	        ]);

	        // Calculate total ml and servings
	        $total_ml = $qty * $unitSize;
	        $total_servings = ($servingSize > 0) ? floor($total_ml / $servingSize) : 0;

	        // Update received qty (allow over-receiving)
	        $newReceived = $item['received_qty'] + $qty;
	        $poItemsModel->update($itemId, [
	            'received_qty' => $newReceived
	        ]);

	        // --- Update stock_level table in ml ---
	        // Always receive into location_id = 1 (default/main location)
	        $location_id = 1;
	        $builder = \Config\Database::connect()->table('stock_levels');
	        $stockRow = $builder->where('product_id', $product_id)
	                            ->where('location_id', $location_id)
	                            ->get()->getRowArray();
	        if ($stockRow) {
	            // Update existing row
	            $builder->where('id', $stockRow['id'])->update([
	                'quantity' => $stockRow['quantity'] + $total_ml
	            ]);
	        } else {
	            // Insert new row
	            $builder->insert([
	                'product_id'  => $product_id,
	                'location_id' => $location_id,
	                'quantity'    => $total_ml
	            ]);
	        }
	        // --- End update stock_level ---

	        // Optionally, log or display this info for admin
	        log_message('info', "Product $product_id: Received $qty units ($total_ml ml, $total_servings servings of $servingSize ml each)");
	    }

	    // Update PO status
	    $items = $poItemsModel->where('po_id', $poId)->findAll();
	    $complete = true;
	    foreach ($items as $item) {
	        if ($item['received_qty'] < $item['qty_ordered']) {
	            $complete = false;
	            break;
	        }
	    }
	    $poModel->update($poId, [
	        'status' => $complete ? 'completed' : 'partial'
	    ]);

	    return redirect()->to('/admin/purchaseorders');
	}

}