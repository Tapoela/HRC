<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">

<div class="card">
<div class="card-header d-flex justify-content-between">
    <h5>Transfer Stock Between Locations</h5>
</div>
<div class="card-body">

    <div class="alert alert-info mb-3">
        Transfer stock between locations by entering the number of <strong>units</strong> to move.<br>
        Each unit = 1 physical item (e.g. 1 Bottle of 750ml, 1 Can of 500ml, 1 Case of 24×340ml).
    </div>

    <form method="post" action="<?= site_url('admin/stock/doTransfer') ?>">

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">From Location</label>
                <select name="location_from" id="location_from" class="form-control" required onchange="location.href='?location_from='+this.value">
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>" <?= ($selected_location == $loc['id']) ? 'selected' : '' ?>><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">To Location</label>
                <select name="location_to" id="location_to" class="form-control" required>
                    <?php foreach($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Unit Description</th>
                    <th>Available</th>
                    <th width="180">Qty to Transfer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product):
                    $unitType      = $product['unit_type']   ?? 'bottle';
                    $unitSizeMl    = $product['unit_size_ml'] ?? null;
                    $available     = (int)($product['qty']   ?? 0);

                    // Build a friendly label e.g. "Bottle (750ml)" or "Can (500ml)"
                    $unitLabels = ['bottle'=>'Bottle','can'=>'Can','case'=>'Case','ml'=>'ml','L'=>'Litre'];
                    $unitLabel  = $unitLabels[$unitType] ?? ucfirst($unitType);
                    $unitDesc   = $unitSizeMl ? "{$unitLabel} ({$unitSizeMl}ml)" : $unitLabel;
                ?>
                <tr>
                    <td><?= esc($product['name']) ?></td>
                    <td>
                        <span class="fw-bold text-primary"><?= esc($unitDesc) ?></span>
                        <?php if(!$unitSizeMl): ?>
                            <br><small class="text-muted">No size set — <a href="<?= site_url('admin/stock/receive') ?>">set via receive</a></small>
                        <?php endif ?>
                    </td>
                    <td><span class="badge bg-secondary fs-6"><?= $available ?> × <?= esc($unitDesc) ?></span></td>
                    <td>
                        <div class="input-group">
                            <input type="number" class="form-control transfer-input"
                                   name="qty[<?= $product['id'] ?>]"
                                   value="0" min="0" max="<?= $available ?>">
                            <button type="button" class="btn btn-outline-primary"
                                onclick="this.previousElementSibling.value = this.previousElementSibling.max">
                                Max
                            </button>
                        </div>
                        <small class="text-muted">= <span class="transfer-total" data-size="<?= $unitSizeMl ?? 0 ?>">0</span> ml total</small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <a href="<?= site_url('admin/stock') ?>" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-warning">Transfer Stock</button>
        </div>

    </form>

</div>
</div>
</div>

<script>
document.querySelectorAll('.transfer-input').forEach(input => {
    input.addEventListener('input', function(){
        let max = parseFloat(this.max);
        if(parseFloat(this.value) > max) this.value = max;
        if(parseFloat(this.value) < 0)   this.value = 0;

        // Update ml total display
        let totalEl = this.closest('td').querySelector('.transfer-total');
        if(totalEl){
            let size = parseFloat(totalEl.dataset.size) || 0;
            totalEl.textContent = size > 0 ? (parseFloat(this.value) * size) + 'ml' : '—';
        }
    });
});
</script>

<?= $this->endSection() ?>
