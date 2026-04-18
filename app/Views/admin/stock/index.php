<?php $this->extend('admin/layout'); ?>
<?php $this->section('content'); ?>
<div class="container-fluid">
    <h1 class="mb-4">Stock Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="<?= site_url('admin/stock/specials') ?>" class="btn btn-warning btn-block">
                <i class="fas fa-star"></i> Manage Specials
            </a>
        </div>
        <!-- Add more stock-related quick links here if needed -->
    </div>
    <!-- Existing stock dashboard content goes here -->
</div>
<?php $this->endSection(); ?>
