<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Reports Dashboard</h3>
    <div class="row">
        <?php if (in_array((int)session('role_id'), [1,4,5,6])): ?>
        <div class="col-md-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body text-center">
                    <h4>Sales Report</h4>
                    <p>View all sales with filters</p>
                    <a href="<?= site_url('admin/reports/sales') ?>" class="btn btn-light">Open Sales Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white mb-3">
                <div class="card-body text-center">
                    <h4>Sales by Product</h4>
                    <p>Grouped sales totals by product</p>
                    <a href="<?= site_url('admin/reports/sales-grouped') ?>" class="btn btn-light">Open Sales by Product</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark mb-3">
                <div class="card-body text-center">
                    <h4>Forecast Report</h4>
                    <p>Suggest what to order based on previous sales</p>
                    <a href="<?= site_url('admin/reports/forecast') ?>" class="btn btn-light">Open Forecast Report</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body text-center">
                    <h4>Sales Graph</h4>
                    <p>Visualize sales totals over time</p>
                    <a href="<?= site_url('admin/reports/sales-graph') ?>" class="btn btn-light">Open Sales Graph</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white mb-3">
                <div class="card-body text-center">
                    <h4>Sales by Product (Graph)</h4>
                    <p>Visualize sales totals per product</p>
                    <a href="<?= site_url('admin/reports/sales-by-product-graph') ?>" class="btn btn-light">Open Sales by Product Graph</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- Add more report cards here as needed -->
    </div>
</div>
<?= $this->endSection() ?>
