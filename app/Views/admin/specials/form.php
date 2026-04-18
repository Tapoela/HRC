<?php $this->extend('layouts/admin'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4"><?= isset($special) ? 'Edit Special' : 'Create Special' ?></h1>
    <form action="<?= isset($special) ? site_url('admin/stock/specials/edit/' . encode_id($special['id'])) : site_url('admin/stock/specials/create') ?>" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($special) ? esc($special['name']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (R)</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= isset($special) ? esc($special['price']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="active" class="form-label">Status</label>
            <select class="form-control" id="active" name="active">
                <option value="1" <?= isset($special) && $special['active'] ? 'selected' : '' ?>>Active</option>
                <option value="0" <?= isset($special) && !$special['active'] ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <!-- Special Items Section -->
        <div class="mb-3">
            <label class="form-label">Bundle Slots (by Category)</label>
            <div id="special-items-list">
                <?php if (!empty($items)): ?>
                    <?php foreach ($items as $i => $item): ?>
                        <div class="row mb-2 special-item-row">
                            <div class="col-md-6">
                                <select name="items[<?= $i ?>][category_id]" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= isset($item['category_id']) && $item['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="items[<?= $i ?>][quantity]" class="form-control" placeholder="Quantity" value="<?= esc($item['quantity'] ?? '') ?>" min="1" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-remove-item">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-secondary" id="add-item-btn">Add Slot</button>
        </div>
        <button type="submit" class="btn btn-success">Save Special</button>
        <a href="<?= site_url('admin/stock/specials') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php if (!empty($categories)): ?>
    <div class="alert alert-info">
        <strong>Category Array (Debug):</strong>
        <pre><?= json_encode($categories, JSON_PRETTY_PRINT) ?></pre>
    </div>
<?php endif; ?>
<script>
    let itemIndex = <?= isset($items) ? count($items) : 0 ?>;
    const categories = <?php echo json_encode($categories ?? []); ?>;
    document.getElementById('add-item-btn').addEventListener('click', function() {
        const list = document.getElementById('special-items-list');
        const row = document.createElement('div');
        row.className = 'row mb-2 special-item-row';
        let catOptions = '<option value="">Select Category</option>';
        categories.forEach(function(cat) {
            catOptions += `<option value="${cat.id}">${cat.name.replace(/"/g, '&quot;')}</option>`;
        });
        row.innerHTML = `
            <div class="col-md-6">
                <select name="items[${itemIndex}][category_id]" class="form-control" required>
                    ${catOptions}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Quantity" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-remove-item">Remove</button>
            </div>
        `;
        list.appendChild(row);
        itemIndex++;
    });
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-item')) {
            e.target.closest('.special-item-row').remove();
        }
    });
</script>
<?php $this->endSection(); ?>
