<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<div class="row">

	<!-- Create PO -->
	<div class="col-md-4">

		<div class="card bg-primary text-white">

			<div class="card-body text-center">

				<h4>Create Purchase Order</h4>

				<p>Create a supplier order</p>

				<a href="<?= site_url('admin/purchaseorders') ?>" class="btn btn-light">
					Open Purchase Orders
				</a>

				<pre>
					Role: <?= session('role_name') ?>
				</pre>

			</div>

		</div>

	</div>


	<!-- Receive Stock -->
	<div class="col-md-4">

		<div class="card bg-success text-white">

			<div class="card-body text-center">

				<h4>Receive Stock</h4>

				<p>Receive supplier deliveries</p>

				<a href="<?= site_url('admin/stock/receive') ?>"
				class="btn btn-light">

				Receive Stock

				</a>

			</div>

		</div>

	</div>


	<!-- Adjust Stock (Admin only) -->
	<?php if(session('role_id') == 1): ?>

	<div class="col-md-4">

		<div class="card bg-danger text-white">

			<div class="card-body text-center">

				<h4>Adjust Stock</h4>

				<p>Correct stock quantities</p>

				<a href="<?= site_url('admin/stock/adjust') ?>"
				class="btn btn-light">

				Adjust Stock

				</a>

			</div>

		</div>

	</div>

	<div class="col-md-4">

		<div class="card bg-info text-white">

			<div class="card-body text-center">

				<h4>Create Product</h4>

				<p>Add new bar items</p>

				<a href="<?= site_url('admin/products/create') ?>"
				class="btn btn-light">

				Create Product

				</a>

			</div>

		</div>

	</div>

	<div class="col-md-4">

		<div class="card bg-info text-white">

			<div class="card-body text-center">

				<h4>Add Category</h4>

				<p>Add new Category</p>

				<a href="<?= site_url('admin/categories') ?>"
                	class="btn btn-light">

				Create Catergory

				</a>

			</div>

		</div>

	</div>

	<?php endif; ?>

</div>

<?= $this->endSection() ?>