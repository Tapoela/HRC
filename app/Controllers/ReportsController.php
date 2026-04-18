<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\Database\BaseBuilder;

class ReportsController extends BaseController
{
    public function salesReport()
    {
        $db = \Config\Database::connect();
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        $builder = $db->table('sale_items si')
            ->select("s.created_at, si.qty, si.price, p.name as product_name, l.name as location_name, CONCAT(u.name, ' ', u.surname) as user_name")
            ->join('sales s', 'si.sale_id = s.id')
            ->join('products p', 'si.product_id = p.id')
            ->join('locations l', 's.location_id = l.id', 'left')
            ->join('users u', 's.user_id = u.id', 'left');
        if ($from) {
            $builder->where('s.created_at >=', $from.' 00:00:00');
        }
        if ($to) {
            $builder->where('s.created_at <=', $to.' 23:59:59');
        }
        $builder->orderBy('s.created_at', 'desc');
        $sales = $builder->get()->getResultArray();
        return view('admin/reports/sales_report', [
            'sales' => $sales,
            'from' => $from,
            'to' => $to
        ]);
    }
    public function salesGrouped()
    {
        $db = \Config\Database::connect();
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        $builder = $db->table('sale_items si')
            ->select('p.name as product_name, SUM(si.qty) as total_qty, SUM(si.price * si.qty) as total_sales')
            ->join('sales s', 'si.sale_id = s.id')
            ->join('products p', 'si.product_id = p.id');
        if ($from) {
            $builder->where('s.created_at >=', $from.' 00:00:00');
        }
        if ($to) {
            $builder->where('s.created_at <=', $to.' 23:59:59');
        }
        $builder->where('s.created_at IS NOT NULL');
        $builder->groupBy('si.product_id');
        $builder->orderBy('total_qty', 'desc');
        $rows = $builder->get()->getResultArray();
        return view('admin/reports/sales_grouped', [
            'rows' => $rows,
            'from' => $from,
            'to' => $to
        ]);
    }
    public function dashboard()
    {
        return view('admin/reports/dashboard');
    }
    public function forecast()
    {
        $db = \Config\Database::connect();
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        // Default to last 30 days if not set
        if (!$from || !$to) {
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime('-30 days'));
        }
        $weeks = max(1, ceil((strtotime($to) - strtotime($from)) / (7*86400)));
        $builder = $db->table('sale_items si')
            ->select('p.name as product_name, SUM(si.qty) as total_qty, 0 as current_stock, 0 as avg_per_week, p.id as product_id')
            ->join('sales s', 'si.sale_id = s.id')
            ->join('products p', 'si.product_id = p.id')
            ->where('p.track_stock', 1)
            ->where('s.created_at >=', $from.' 00:00:00')
            ->where('s.created_at <=', $to.' 23:59:59')
            ->groupBy('si.product_id');
        $rows = $builder->get()->getResultArray();
        // Get current stock for all products
        $stockRows = $db->table('stock_levels')->select('product_id, SUM(quantity) as qty')->groupBy('product_id')->get()->getResultArray();
        $stockMap = [];
        foreach($stockRows as $s) $stockMap[$s['product_id']] = $s['qty'];
        foreach($rows as &$row) {
            $row['avg_per_week'] = $weeks > 0 ? $row['total_qty']/$weeks : 0;
            $row['current_stock'] = $stockMap[$row['product_id']] ?? 0;
        }
        unset($row);
        return view('admin/reports/forecast', [
            'rows' => $rows,
            'from' => $from,
            'to' => $to
        ]);
    }
    public function salesGraph()
    {
        $db = \Config\Database::connect();
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        if (!$from || !$to) {
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime('-30 days'));
        }
        $builder = $db->table('sales')
            ->select('DATE(created_at) as sale_date, SUM(total) as total_sales')
            ->where('created_at >=', $from.' 00:00:00')
            ->where('created_at <=', $to.' 23:59:59')
            ->groupBy('sale_date')
            ->orderBy('sale_date', 'asc');
        $rows = $builder->get()->getResultArray();
        $labels = [];
        $totals = [];
        foreach($rows as $row) {
            $labels[] = $row['sale_date'];
            $totals[] = (float)$row['total_sales'];
        }
        $chartData = [ 'labels' => $labels, 'totals' => $totals ];
        return view('admin/reports/sales_graph', [
            'chartData' => $chartData,
            'from' => $from,
            'to' => $to
        ]);
    }
    public function salesByProductGraph()
    {
        $db = \Config\Database::connect();
        $from = $this->request->getGet('from');
        $to = $this->request->getGet('to');
        if (!$from || !$to) {
            $to = date('Y-m-d');
            $from = date('Y-m-d', strtotime('-30 days'));
        }
        $builder = $db->table('sale_items si')
            ->select('p.name as product_name, SUM(si.qty) as total_qty')
            ->join('sales s', 'si.sale_id = s.id')
            ->join('products p', 'si.product_id = p.id')
            ->where('p.track_stock', 1)
            ->where('s.created_at >=', $from.' 00:00:00')
            ->where('s.created_at <=', $to.' 23:59:59')
            ->groupBy('si.product_id')
            ->orderBy('total_qty', 'desc');
        $rows = $builder->get()->getResultArray();
        $labels = [];
        $totals = [];
        foreach($rows as $row) {
            $labels[] = $row['product_name'];
            $totals[] = (int)$row['total_qty'];
        }
        $chartData = [ 'labels' => $labels, 'totals' => $totals ];
        return view('admin/reports/sales_by_product_graph', [
            'chartData' => $chartData,
            'from' => $from,
            'to' => $to
        ]);
    }
}
