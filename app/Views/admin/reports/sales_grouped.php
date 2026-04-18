<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Sales by Product</h3>
    <?php if (!in_array((int)session('role_id'), [1,4,5,6])): ?>
        <div class="alert alert-danger mt-4">You do not have permission to view this report.</div>
    <?php return; endif; ?>
    <form method="get" class="row mb-3">
        <div class="col-md-3">
            <label>From Date</label>
            <input type="date" name="from" value="<?= esc($from ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label>To Date</label>
            <input type="date" name="to" value="<?= esc($to ?? '') ?>" class="form-control">
        </div>
        <div class="col-md-3 align-self-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Sold</th>
                <th>Total Sales (R)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?= esc($row['product_name']) ?></td>
                <td><?= esc($row['total_qty']) ?></td>
                <td><?= number_format($row['total_sales'],2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
