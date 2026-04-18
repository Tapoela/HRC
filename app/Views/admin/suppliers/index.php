<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Suppliers</h2>
        <a href="<?= site_url('admin/suppliers/create') ?>" class="btn btn-primary">Add Supplier</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($suppliers as $supplier): ?>
            <tr>
                <td><?= esc($supplier['name']) ?></td>
                <td><?= esc($supplier['contact_person']) ?></td>
                <td><?= esc($supplier['phone']) ?></td>
                <td><?= esc($supplier['email']) ?></td>
                <td><?= $supplier['active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
                <td>
                    <a href="<?= site_url('admin/suppliers/view/'.encode_id($supplier['id'])) ?>" class="btn btn-sm btn-info">View</a>
                    <a href="<?= site_url('admin/suppliers/edit/'.encode_id($supplier['id'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
