<?php
// Admin Team Selection List View
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid">
    <h1 class="mb-4">Team Selections</h1>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="<?= site_url('admin/fixtures') ?>" class="btn btn-secondary">Back to Fixtures</a>
        <?php if (isset($canEdit) && $canEdit): ?>
            <a href="<?= site_url('admin/fixtures') ?>" class="btn btn-primary">Create Team Selection (Choose Fixture)</a>
        <?php endif; ?>
    </div>
    <?php if (session('success')): ?>
        <div class="alert alert-success"> <?= session('success') ?> </div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="alert alert-danger"> <?= session('error') ?> </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover" id="teams-table">
                <thead>
                    <tr>
                        <th>Fixture</th>
                        <th>Team Name</th>
                        <th>Coach</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <tr>
                            <td><?= esc($team['fixture_name']) ?></td>
                            <td><?= esc($team['team_name']) ?></td>
                            <td><?= esc($team['coach_name']) ?></td>
                            <td><?= date('Y-m-d', strtotime($team['created_at'])) ?></td>
                            <td>
                                <a href="<?= site_url('admin/teams/view/' . encode_id($team['id'])) ?>" class="btn btn-sm btn-info">View</a>
                                <?php if (isset($canEdit) && $canEdit): ?>
                                    <a href="<?= site_url('admin/teams/edit/' . encode_id($team['id'])) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="<?= site_url('admin/teams/delete/' . encode_id($team['id'])) ?>" method="post" style="display:inline-block" onsubmit="return confirm('Delete this team selection?');">
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?= site_url('admin/teams/print/' . encode_id($team['id'])) ?>" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No team selections found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>
