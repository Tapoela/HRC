<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Sales Report</h3>
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
                <th>Date</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Location</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($sales as $row): ?>
            <tr>
                <td><?= $row['created_at'] ? esc($row['created_at']) : '-' ?></td>
                <td><?= esc($row['product_name']) ?></td>
                <td><?= esc($row['qty']) ?></td>
                <td>R<?= number_format($row['price'],2) ?></td>
                <td><?= esc($row['location_name'] ?? '-') ?></td>
                <td><?= esc($row['user_name'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
