<?php $this->extend('layouts/admin'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4">Specials</h1>
    <a href="<?= site_url('admin/stock/specials/create') ?>" class="btn btn-primary mb-3">Create New Special</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($specials)): ?>
                <?php foreach ($specials as $special): ?>
                    <tr>
                        <td><?= esc($special['name']) ?></td>
                        <td>R<?= number_format($special['price'], 2) ?></td>
                        <td><?= $special['active'] ? 'Active' : 'Inactive' ?></td>
                        <td>
                            <a href="<?= site_url('admin/stock/specials/edit/' . encode_id($special['id'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?= site_url('admin/stock/specials/delete/' . encode_id($special['id'])) ?>" method="post" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">No specials found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $this->endSection(); ?>
