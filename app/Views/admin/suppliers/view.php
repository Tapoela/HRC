<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h2>Supplier Details</h2>
    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <td><?= esc($supplier['name']) ?></td>
        </tr>
        <tr>
            <th>Contact Person</th>
            <td><?= esc($supplier['contact_person']) ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?= esc($supplier['phone']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= esc($supplier['email']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= $supplier['active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
        </tr>
    </table>
    <a href="<?= site_url('admin/suppliers/edit/'.encode_id($supplier['id'])) ?>" class="btn btn-warning">Edit</a>
    <a href="<?= site_url('admin/suppliers') ?>" class="btn btn-secondary">Back to List</a>
</div>

<?= $this->endSection() ?>
