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

	<!-- Transfer Stock -->
	<div class="col-md-4">

		<div class="card bg-warning text-dark">

			<div class="card-body text-center">

				<h4>Transfer Stock</h4>

				<p>Move stock between locations</p>

				<a href="<?= site_url('admin/stock/transfer') ?>" class="btn btn-light">
					Transfer Stock
				</a>

			</div>

		</div>

	</div>

	<!-- Suppliers Card -->
	<div class="col-md-4">
		<div class="card bg-success text-white" style="cursor:pointer;" onclick="window.location='<?= site_url('admin/suppliers') ?>'">
			<div class="card-body text-center">
				<h4>Suppliers</h4>
				<p>Manage suppliers and contacts</p>
				<a href="<?= site_url('admin/suppliers') ?>" class="btn btn-light">Open Suppliers</a>
			</div>
		</div>
	</div>

	<!-- Specials Card -->
	<div class="col-md-4">
		<div class="card bg-secondary text-white" style="cursor:pointer;" onclick="window.location='<?= site_url('admin/stock/specials') ?>'">
			<div class="card-body text-center">
				<h4>Specials</h4>
				<p>Manage product bundles and specials</p>
				<a href="<?= site_url('admin/stock/specials') ?>" class="btn btn-light">Open Specials</a>
			</div>
		</div>
	</div>

</div>

<?= $this->endSection() ?>