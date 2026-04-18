<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<div class="card">

		<div class="card-header">

			<h3 class="card-title">Products</h3>

			<a href="<?= site_url('admin/products/create') ?>"
			class="btn btn-primary float-right">
			Add Product
			</a>

		</div>

		<div class="card-body">

			<table class="table table-bordered">

				<thead>

					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Type</th>
						<th>Sell Price</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>

				</thead>

				<tbody>

					<?php foreach($products as $p): ?>

					<tr>

						<td><?= $p['id'] ?></td>
						<td><?= esc($p['name']) ?></td>
						<td><?= esc($p['product_type']) ?></td>
						<td>R<?= $p['sell_price'] ?></td>
						<td><?= $p['active'] ? 'Active' : 'Disabled' ?></td>
						<td width="160">
							<a href="<?= site_url('admin/products/edit/'.encode_id($p['id'])) ?>" class="btn btn-warning btn-sm">
								<i class="fas fa-edit"></i> Edit
							</a>
						</td>

					</tr>

					<?php endforeach ?>

				</tbody>

			</table>

		</div>

	</div>

</div>

<?= $this->endSection() ?>