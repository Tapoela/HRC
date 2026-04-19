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
				    <select name="unit_type" id="unit_type" class="form-control" required onchange="updateSizeHints()">
				        <option value="">Select Unit</option>
				        <option value="bottle">Bottle</option>
				        <option value="can">Can</option>
				        <option value="case">Case</option>
				        <option value="glass">Glass</option>
				        <option value="shot">Shot</option>
				        <option value="plate">Plate</option>
				        <option value="combo">Combo</option>
				        <option value="unit">Unit</option>
				    </select>
				</div>

				<div class="row">
				    <div class="col-md-6 form-group">
				        <label>Unit Size (ml) <small class="text-muted">— size of 1 unit e.g. 750ml bottle, 500ml can, 25ml shot</small></label>
				        <input type="number" name="unit_size_ml" id="unit_size_ml" class="form-control" min="1" placeholder="e.g. 750">
				        <small id="unit_size_hint" class="text-info"></small>
				    </div>
				    <div class="col-md-6 form-group">
				        <label>Serving Size (ml) <small class="text-muted">— ml sold per serving e.g. 25ml shot, 340ml beer, 150ml wine</small></label>
				        <input type="number" name="serving_size_ml" id="serving_size_ml" class="form-control" min="1" placeholder="e.g. 25">
				        <small id="serving_hint" class="text-info"></small>
				    </div>
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

<script>
const hints = {
    bottle: { unit: '750', serving: '750', note: 'e.g. 750ml wine/spirits bottle' },
    can:    { unit: '500', serving: '500', note: 'e.g. 500ml beer can' },
    shot:   { unit: '25',  serving: '25',  note: 'SA standard shot = 25ml' },
    glass:  { unit: '150', serving: '150', note: 'e.g. 150ml wine glass' },
    case:   { unit: '',    serving: '',    note: 'Enter total ml in the case' },
    plate:  { unit: '',    serving: '',    note: 'Not applicable for food' },
    combo:  { unit: '',    serving: '',    note: 'Not applicable for combos' },
    unit:   { unit: '',    serving: '',    note: '' },
};
function updateSizeHints(){
    let type = document.getElementById('unit_type').value;
    let h = hints[type] || {};
    if(h.unit) document.getElementById('unit_size_ml').value = h.unit;
    if(h.serving) document.getElementById('serving_size_ml').value = h.serving;
    document.getElementById('unit_size_hint').textContent = h.note || '';
    document.getElementById('serving_hint').textContent = type === 'shot' ? '⚡ SA standard = 25ml' : '';
}
</script>