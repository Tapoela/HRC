<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between">
            <span>Edit Product</span>
            <a href="<?= site_url('admin/products') ?>" class="btn btn-secondary btn-sm">Back</a>
        </div>

        <div class="card-body">

            <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <form method="post" action="<?= site_url('admin/products/update/'.encode_id($product['id'])) ?>">

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" class="form-control" required maxlength="100"
                           value="<?= esc(old('name', $product['name'])) ?>">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select Category</option>
                        <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= old('category_id', $product['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                            <?= esc($cat['name']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Unit Type</label>
                    <select name="unit_type" id="unit_type" class="form-control" required onchange="updateSizeHints()">
                        <option value="">Select Unit</option>
                        <?php foreach(['bottle'=>'Bottle','can'=>'Can','case'=>'Case','glass'=>'Glass','shot'=>'Shot','plate'=>'Plate','combo'=>'Combo','unit'=>'Unit'] as $val=>$label): ?>
                        <option value="<?= $val ?>" <?= old('unit_type', $product['unit_type']) == $val ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Unit Size (ml) <small class="text-muted">— size of 1 unit e.g. 750ml bottle, 500ml can, 25ml shot</small></label>
                        <input type="number" name="unit_size_ml" id="unit_size_ml" class="form-control" min="1"
                               placeholder="e.g. 750"
                               value="<?= esc(old('unit_size_ml', $product['unit_size_ml'] ?? '')) ?>">
                        <small id="unit_size_hint" class="text-info"></small>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Serving Size (ml) <small class="text-muted">— ml sold per serving e.g. 25ml shot, 340ml beer, 150ml wine</small></label>
                        <input type="number" name="serving_size_ml" id="serving_size_ml" class="form-control" min="1"
                               placeholder="e.g. 25"
                               value="<?= esc(old('serving_size_ml', $product['serving_size_ml'] ?? '')) ?>">
                        <small id="serving_hint" class="text-info"></small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Product Type</label>
                    <select name="product_type" class="form-control" required>
                        <option value="">Select Type</option>
                        <?php foreach($types as $type): ?>
                        <option value="<?= $type['id'] ?>" <?= old('product_type', $product['product_type']) == $type['id'] ? 'selected' : '' ?>>
                            <?= esc($type['type_name']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Selling Price (R)</label>
                        <input type="number" step="0.01" min="0" name="sell_price" class="form-control" required
                               value="<?= esc(old('sell_price', $product['sell_price'])) ?>">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Cost Price (R)</label>
                        <input type="number" step="0.01" min="0" name="cost_price" class="form-control" required
                               value="<?= esc(old('cost_price', $product['cost_price'])) ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" name="active" id="active" class="form-check-input" value="1"
                               <?= $product['active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                </div>

                <button class="btn btn-success">Save Changes</button>

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
    // Only auto-fill if fields are empty
    let unitField    = document.getElementById('unit_size_ml');
    let servingField = document.getElementById('serving_size_ml');
    if(!unitField.value    && h.unit)    unitField.value    = h.unit;
    if(!servingField.value && h.serving) servingField.value = h.serving;
    document.getElementById('unit_size_hint').textContent  = h.note || '';
    document.getElementById('serving_hint').textContent    = type === 'shot' ? '⚡ SA standard = 25ml' : '';
}
// Show hint on load
updateSizeHints();
</script>
