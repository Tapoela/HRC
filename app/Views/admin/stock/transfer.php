<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="container-fluid">

<div class="card">
<div class="card-header d-flex justify-content-between">
    <h5>Transfer Stock Between Locations</h5>
</div>
<div class="card-body">

    <div class="alert alert-info mb-3">
        <strong>Unit Size</strong> = size of each bottle/can in ml (e.g. beer = 340ml, wine = 750ml, spirits = 750ml).<br>
        <strong>Serving Size</strong> = ml per serving — SA standard shot = <strong>25ml</strong> (e.g. spirits = 25, beer = 340, wine = 150).<br>
        If the product was set up before, these will be pre-filled automatically.
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
                    <th>Available Qty</th>
                    <th>Unit Type</th>
                    <th>Unit Size (ml)</th>
                    <th>Serving Size (ml)</th>
                    <th width="150">Qty to Transfer</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($products as $product):
                    $unitType      = $product['unit_type']      ?? 'bottle';
                    $unitSizeMl    = $product['unit_size_ml']    ?? '';
                    $servingSizeMl = $product['serving_size_ml'] ?? '';
                    $alreadySet    = !empty($unitSizeMl) && !empty($servingSizeMl);
                    $available     = (int)($product['qty'] ?? 0);
                ?>
                <tr>
                    <td><?= esc($product['name']) ?></td>
                    <td><?= $available ?></td>
                    <td>
                        <select name="unit_type[<?= $product['id'] ?>]" class="form-control">
                            <?php foreach(['bottle'=>'Bottle','can'=>'Can','case'=>'Case','ml'=>'ml','L'=>'Litre'] as $val=>$label): ?>
                            <option value="<?= $val ?>" <?= $unitType==$val?'selected':'' ?>><?= $label ?></option>
                            <?php endforeach ?>
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control"
                               name="unit_size_ml[<?= $product['id'] ?>]"
                               value="<?= esc($unitSizeMl) ?>"
                               min="1" placeholder="e.g. 340">
                        <?php if($alreadySet): ?><small class="text-muted">Saved on product</small><?php endif ?>
                    </td>
                    <td>
                        <input type="number" class="form-control"
                               name="serving_size_ml[<?= $product['id'] ?>]"
                               value="<?= esc($servingSizeMl) ?>"
                               min="1" placeholder="e.g. 25 (SA shot)">
                        <?php if($alreadySet): ?><small class="text-muted">Saved on product</small><?php endif ?>
                    </td>
                    <td>
                        <input type="number" class="form-control transfer-input"
                               name="qty[<?= $product['id'] ?>]"
                               value="0" min="0" max="<?= $available ?>">
                        <button type="button" class="btn btn-sm btn-outline-primary mt-1"
                            onclick="this.previousElementSibling.value = this.previousElementSibling.max">
                            Max
                        </button>
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
        if(parseFloat(this.value) > max){
            this.value = max;
        }
        if(parseFloat(this.value) < 0){
            this.value = 0;
        }
    });
});
</script>

<?= $this->endSection() ?>
