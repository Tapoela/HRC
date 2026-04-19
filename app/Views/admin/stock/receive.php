<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2>Receive Stock - PO #<?= $po['id'] ?></h2>

<form method="post" action="<?= base_url('admin/purchaseorders/processReceive') ?>">

<input type="hidden" name="po_id" value="<?= encode_id($po['id']) ?>">

<div class="container-fluid">

<div class="card">

<div class="card-header">
    <h5>Receive Stock - PO #<?= $po['po_number'] ?></h5>
</div>

<div class="card-body">

<div class="alert alert-info mb-3">
    <strong>Unit Size</strong> = size of each bottle/can in ml (e.g. beer = 340ml, wine = 750ml, spirits = 750ml).<br>
    <strong>Serving Size</strong> = ml per serving — SA standard shot = <strong>25ml</strong> (e.g. spirits = 25, beer = 340, wine = 150).<br>
    If the product was received before, these will be pre-filled automatically.
</div>

<table class="table table-bordered table-striped align-middle">

<thead class="table-dark">
<tr>
    <th>Product</th>
    <th>Ordered</th>
    <th>Received</th>
    <th>Remaining</th>
    <th>Unit Type</th>
    <th>Unit Size (ml)</th>
    <th>Serving Size (ml)</th>
    <th width="150">Receive Now</th>
</tr>
</thead>

<tbody>

<?php foreach($items as $item): 
    $overReceived = $item['received_qty'] > $item['qty_ordered'];
    $itemId = $item['id'];
    $remaining = $item['qty_ordered'] - $item['received_qty'];

    // Use saved product values as defaults
    $unitType    = $item['unit_type']    ?? $item['product_unit_type']    ?? 'bottle';
    $unitSizeMl  = $item['unit_size_ml'] ?? $item['product_unit_size_ml'] ?? '';
    $servingSizeMl = $item['serving_size_ml'] ?? $item['product_serving_size_ml'] ?? '';

    $alreadySet = !empty($unitSizeMl) && !empty($servingSizeMl);
?>

<tr>

<td><?= esc($item['product_name']) ?></td>

<td id="qty_ordered_<?= $itemId ?>"><?= $item['qty_ordered'] ?></td>

<td><?= $item['received_qty'] ?></td>

<td>
    <?php if ($overReceived): ?>
        <span class="badge bg-danger">
            Over-received by <?= $item['received_qty'] - $item['qty_ordered'] ?>
        </span>
    <?php else: ?>
        <span class="badge bg-info">
            <?= $remaining ?>
        </span>
    <?php endif; ?>
</td>
<td>
    <select name="unit_type[<?= $itemId ?>]" class="form-control">
        <?php foreach(['bottle'=>'Bottle','can'=>'Can','case'=>'Case','ml'=>'ml','L'=>'Litre'] as $val=>$label): ?>
        <option value="<?= $val ?>" <?= $unitType==$val?'selected':'' ?>><?= $label ?></option>
        <?php endforeach ?>
    </select>
</td>
<td>
    <input type="number"
           class="form-control"
           name="unit_size_ml[<?= $itemId ?>]"
           value="<?= esc($unitSizeMl) ?>"
           min="1"
           placeholder="e.g. 340">
    <?php if($alreadySet): ?>
        <small class="text-muted">Saved on product</small>
    <?php endif ?>
</td>
<td>
    <input type="number"
           class="form-control"
           name="serving_size_ml[<?= $itemId ?>]"
           value="<?= esc($servingSizeMl) ?>"
           min="1"
           placeholder="e.g. 25 (SA shot)"
    <?php if($alreadySet): ?>
        <small class="text-muted">Saved on product</small>
    <?php endif ?>
</td>
<td>
    <input type="number" 
           class="form-control receive-input"
           name="receive_qty[<?= $itemId ?>]"
           value="0"
           min="0"
           max="<?= $remaining ?>"
           step="1">
    <button type="button" 
        class="btn btn-sm btn-outline-primary mt-1"
        onclick="this.previousElementSibling.value = this.previousElementSibling.max">
        Max
    </button>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<div class="d-flex justify-content-between mt-3">

<a href="<?= site_url('admin/purchaseorders') ?>" 
   class="btn btn-secondary">
   Cancel
</a>

<button type="submit" class="btn btn-success">
    Receive Stock
</button>

</div>

</div>
</div>
</div>

</form>

<script>
document.querySelectorAll('.receive-input').forEach(input => {
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