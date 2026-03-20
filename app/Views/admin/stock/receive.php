<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h2>Receive Stock - PO #<?= $po['id'] ?></h2>

<form method="post" action="<?= base_url('admin/purchaseorders/processReceive') ?>">

<input type="hidden" name="po_id" value="<?= $po['id'] ?>">

<div class="container-fluid">

<div class="card">

<div class="card-header">
    <h5>Receive Stock - PO #<?= $po['po_number'] ?></h5>
</div>

<div class="card-body">

<form method="post" action="<?= base_url('admin/purchaseorders/processReceive') ?>">

<input type="hidden" name="po_id" value="<?= $po['id'] ?>">

<table class="table table-bordered table-striped align-middle">

<thead class="table-dark">
<tr>
    <th>Product</th>
    <th>Ordered</th>
    <th>Received</th>
    <th>Remaining</th>
    <th width="150">Receive Now</th>
</tr>
</thead>

<tbody>

<?php foreach($items as $item): 
    $remaining = $item['qty_ordered'] - $item['received_qty'];
?>

<tr>

<td><?= esc($item['product_name']) ?></td>

<td><?= $item['qty_ordered'] ?></td>

<td><?= $item['received_qty'] ?></td>

<td>
    <span class="badge bg-info">
        <?= $remaining ?>
    </span>
</td>

<td>
    <input type="number" 
           class="form-control receive-input"
           name="receive_qty[<?= $item['id'] ?>]"
           value="0"
           min="0"
           max="<?= $remaining ?>"
           step="1">
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

<button type="button" 
        class="btn btn-sm btn-outline-primary mt-1"
        onclick="this.previousElementSibling.value = this.previousElementSibling.max">
    Max
</button>

</div>

</form>

</div>
</div>
</div>

<br>
<button type="submit">Receive Stock</button>

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