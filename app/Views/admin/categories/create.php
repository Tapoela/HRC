<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

	<div class="card">

	<div class="card-header">
		Create Category
	</div>

		<div class="card-body">

			<?php if(session()->getFlashdata('errors')): ?>

			<div class="alert alert-danger">

				<?php foreach(session()->getFlashdata('errors') as $error): ?>

				<p><?= esc($error) ?></p>

				<?php endforeach ?>

			</div>

			<?php endif ?>

			<form method="post" action="<?= site_url('admin/categories/store') ?>">

				<div class="form-group">

					<label>Category Name</label>

					<input type="text"
					name="name"
					class="form-control"
					required>

				</div>

				<br>

				<button class="btn btn-success">
				Save Category
				</button>

			</form>

		</div>

	</div>

</div>

<?= $this->endSection() ?>