<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <h3>Transfer Stock Between Locations</h3>
    <form method="post" action="<?= site_url('admin/stock/transfer') ?>">
        <div class="row mb-3">
            <div class="col-md-4">
                <label>From Location</label>
                <select name="location_from" id="location_from" class="form-control" required onchange="location.href='?location_from='+this.value">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>" <?= ($selected_location == $loc['id']) ? 'selected' : '' ?>><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>To Location</label>
                <select name="location_to" id="location_to" class="form-control" required>
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Current Qty</th>
                    <th>Qty to Transfer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product): ?>
                <tr>
                    <td><?= esc($product['name']) ?></td>
                    <td><?= (int)($product['qty'] ?? 0) ?></td>
                    <td><input type="number" name="qty[<?= $product['id'] ?>]" min="0" max="<?= (int)($product['qty'] ?? 0) ?>" class="form-control" value="0"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-warning">Transfer Stock</button>
    </form>
</div>
<?= $this->endSection() ?>
