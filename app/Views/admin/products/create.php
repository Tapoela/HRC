<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<div class="card">

		<div class="card-header">
		Create Product
		</div>

		<div class="card-body">

				<?php if(session()->getFlashdata('errors')): ?>

				<div class="alert alert-danger">

				<?php foreach(session()->getFlashdata('errors') as $error): ?>

				<p><?= esc($error) ?></p>

				<?php endforeach ?>

				</div>

				<?php endif ?>

			<form method="post" action="<?= site_url('admin/products/store') ?>">

				<div class="form-group">

					<label>Product Name</label>

					<input type="text"
					name="name"
					class="form-control"
					required
					maxlength="100">

				</div>


				<div class="form-group">

					<label>Category</label>

					<select name="category_id" class="form-control" required>

						<option value="">Select Category</option>

						<?php foreach($categories as $category): ?>

						<option value="<?= $category['id'] ?>">
						<?= esc($category['name']) ?>
						</option>

						<?php endforeach; ?>

					</select>

				</div>

				<div class="form-group">

				    <label>Unit Type</label>

				    <select name="unit_type" class="form-control" required>

				        <option value="">Select Unit</option>
				        <option value="unit">Unit</option>
				        <option value="bottle">Bottle</option>
				        <option value="can">Can</option>
				        <option value="glass">Glass</option>
				        <option value="shot">Shot</option>
				        <option value="plate">Plate</option>
				        <option value="combo">Combo</option>

				    </select>

				</div>

				<div class="form-group">


					<label>Product Type</label>

					<select name="product_type" class="form-control" required>

						<option value="">Select Type</option>

						<?php foreach($types as $type): ?>

						<option value="<?= $type['id'] ?>">
						<?= esc($type['type_name']) ?>
						</option>

						<?php endforeach; ?>

					</select>

				</div>


				<div class="form-group">

					<label>Selling Price</label>

					<input type="number"
					step="0.01"
					min="0"
					name="sell_price"
					class="form-control"
					required>

				</div>


				<div class="form-group">

				<label>Cost Price</label>

					<input type="number"
					step="0.01"
					min="0"
					name="cost_price"
					class="form-control"
					required>

				</div>


			<button class="btn btn-success">
			Save Product
			</button>

			</form>

		</div>

	</div>

</div>

<?= $this->endSection() ?>