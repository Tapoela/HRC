<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h2><?= isset($supplier) ? (isset($supplier['id']) ? 'Edit' : 'Add') : 'Add' ?> Supplier</h2>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($supplier) ? esc($supplier['name']) : '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact_person" class="form-label">Contact Person</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person" value="<?= isset($supplier) ? esc($supplier['contact_person']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= isset($supplier) ? esc($supplier['phone']) : '' ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= isset($supplier) ? esc($supplier['email']) : '' ?>">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="active" name="active" value="1" <?= (isset($supplier) && $supplier['active']) || !isset($supplier) ? 'checked' : '' ?>>
            <label class="form-check-label" for="active">Active</label>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="<?= site_url('admin/suppliers') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
