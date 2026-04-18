<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<a href="/admin/stock/po" class="btn btn-secondary mb-3">&larr; Back to PO List</a>

	<div class="card">

		<div class="card-header">
			Purchase Order: <?= $po['po_number'] ?>
		</div>

		<div class="card-body">

			<table class="table table-bordered">

				<tr>
					<th>Date</th>
					<td><?= $po['order_date'] ?></td>
				</tr>

				<tr>
					<th>Status</th>
					<td><?= $po['status'] ?></td>
				</tr>

			</table>

			<h5>Items</h5>

			<table class="table table-bordered">

				<thead>
					<tr>
						<th>Product</th>
						<th>Qty</th>
						<th>Cost Price</th>
					</tr>
				</thead>

				<tbody>

					<?php foreach($items as $item): ?>

					<tr>
						<td><?= $item['name'] ?></td>
						<td><?= $item['qty_ordered'] ?></td>
						<td><?= $item['cost_price'] ?></td>
					</tr>

					<?php endforeach ?>

				</tbody>

			</table>

			<div class="approvalBar">

			<?php foreach($approvals as $a): ?>

			<div class="approvalStep <?= $a['status']=='approved'?'approved':'' ?>">

			<div class="stepRole">
			<?= $a['approver_role'] ?>
			</div>

			<div class="stepStatus">

			<?php if($a['status']=='approved'): ?>

			✔ Approved

			<?php else: ?>

			Pending

			<?php endif ?>

			</div>

			</div>

			<?php endforeach ?>

			</div>

		</div>

	</div>

</div>

<?= $this->endSection() ?>