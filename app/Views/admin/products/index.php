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

					</tr>

					<?php endforeach ?>

				</tbody>

			</table>

		</div>

	</div>

</div>

<?= $this->endSection() ?>