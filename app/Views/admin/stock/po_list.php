<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<div class="card">

					<span>Purchase Orders</span>

		<div class="card-header d-flex justify-content-between">

			<a href="<?= site_url('admin/purchaseorders/create') ?>"
			class="btn btn-success btn-sm">
			Create Purchase Order
			</a>

		</div>

		<div class="card-body">

			<table class="table table-bordered table-striped">

				<thead>

				<tr>
				<th>PO Number</th>
				<th>Supplier</th>
				<th>Date</th>
				<th>Total</th>
				<th>Order Status</th>
				<th>Approval Status</th>
				<th width="260">Actions</th>
				</tr>

				</thead>

				<tbody>

					<?php foreach($orders as $order): ?>

					<tr>

						<td><?= $order['po_number'] ?></td>

						<td><?= esc($order['supplier_name']) ?></td>

						<td><?= $order['order_date'] ?></td>

						<td>R <?= number_format($order['total_amount'],2) ?></td>

						<!-- ORDER STATUS -->
						<td>

							<?php if($order['status']=='pending'): ?>

							<span class="badge bg-warning text-dark">Pending</span>

							<?php elseif($order['status']=='ordered'): ?>

							<span class="badge bg-primary">Ordered</span>

							<?php elseif($order['status']=='received'): ?>

							<span class="badge bg-success">Received</span>

							<?php else: ?>

							<span class="badge bg-secondary"><?= esc($order['status']) ?></span>

							<?php endif ?>

						</td>


						<!-- APPROVAL STATUS -->
						<td>

							<?php if($order['approval_status']=='approved'): ?>

							<span class="badge bg-success">Approved</span>

							<?php elseif($order['approval_status']=='partially_approved'): ?>

							<span class="badge bg-info">Partially Approved</span>

							<?php else: ?>

							<span class="badge bg-warning text-dark">Pending Approval</span>

							<?php endif ?>

						</td>

						<!-- ACTION BUTTONS -->
						<td>

							<!-- VIEW -->
							<a href="<?= site_url('admin/purchaseorders/view/'.encode_id($order['id'])) ?>"
							class="btn btn-info btn-sm">
							View
							</a>


							<!-- EDIT (only before approval) -->
							<?php if(strtolower($order['approval_status']) !== 'approved'): ?>

							<a href="<?= site_url('admin/purchaseorders/edit/'.encode_id($order['id'])) ?>"
							class="btn btn-warning btn-sm">
							Edit
							</a>

							<?php endif ?>


							<!-- APPROVE -->
							<?php if(
							    strtolower($order['approval_status']) !== 'approved' &&
							    $order['status'] !== 'completed' &&
							    (
							        $order['next_approver_role_id'] == session('role_id') ||
							        $order['next_approver_role_id'] === null ||
							        session('role_id') == 1
							    )
							): ?>

							<a href="<?= site_url('admin/purchaseorders/approve/'.encode_id($order['id'])) ?>"
							class="btn btn-success btn-sm">
							Approve
							</a>

							<?php endif ?>


							<!-- PDF -->
							<?php if($order['approval_status'] == 'approved'): ?>

							<a href="<?= site_url('admin/purchaseorders/pdf/'.encode_id($order['id'])) ?>" 
							   target="_blank"
							   class="btn btn-danger btn-sm">
							   PDF
							</a>

							<?php endif ?>


							<!-- RECEIVE -->
							<?php
							$canReceive = (
							    strtolower($order['approval_status']) === 'approved' &&
							    !in_array(strtolower($order['status']), ['received', 'completed'])
							);
							?>

							<?php if($canReceive): ?>

							<a href="<?= site_url('admin/purchaseorders/receive/'.encode_id($order['id'])) ?>"
							class="btn btn-primary btn-sm">
							Receive
							</a>

							<?php endif ?>

						</td>

					</tr>

					<?php endforeach ?>

				</tbody>

			</table>

		</div>

	</div>

</div>

<?= $this->endSection() ?>