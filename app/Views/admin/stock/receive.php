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
    <select name="unit_type[<?= $itemId ?>]" class="form-control" required>
        <option value="bottle" <?= (isset($item['unit_type']) && $item['unit_type'] == 'bottle') ? 'selected' : '' ?>>Bottle</option>
        <option value="can" <?= (isset($item['unit_type']) && $item['unit_type'] == 'can') ? 'selected' : '' ?>>Can</option>
        <option value="case" <?= (isset($item['unit_type']) && $item['unit_type'] == 'case') ? 'selected' : '' ?>>Case</option>
        <option value="ml" <?= (isset($item['unit_type']) && $item['unit_type'] == 'ml') ? 'selected' : '' ?>>ml</option>
        <option value="L" <?= (isset($item['unit_type']) && $item['unit_type'] == 'L') ? 'selected' : '' ?>>Litre</option>
    </select>
</td>
<td>
    <input type="number"
           class="form-control"
           name="unit_size_ml[<?= $itemId ?>]"
           value="<?= isset($item['unit_size_ml']) ? esc($item['unit_size_ml']) : '' ?>"
           min="1"
           placeholder="Unit Size (ml)"
           required>
</td>
<td>
    <input type="number"
           class="form-control"
           name="serving_size_ml[<?= $itemId ?>]"
           value="<?= isset($item['serving_size_ml']) ? esc($item['serving_size_ml']) : '' ?>"
           min="1"
           placeholder="Serving Size (ml)"
           required>
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