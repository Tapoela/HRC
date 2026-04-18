<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Forecast Report</h3>
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
            <button class="btn btn-primary">Generate</button>
        </div>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Sold</th>
                <th>Avg/Week</th>
                <th>Current Stock</th>
                <th>Suggested Reorder</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?= esc($row['product_name']) ?></td>
                <td><?= esc($row['total_qty']) ?></td>
                <td><?= number_format($row['avg_per_week'],2) ?></td>
                <td><?= esc($row['current_stock']) ?></td>
                <td><?= max(0, ceil($row['avg_per_week']*2 - $row['current_stock'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <small class="text-muted">Suggested reorder = 2 weeks avg sales minus current stock (never negative).</small>
</div>
<?= $this->endSection() ?>
