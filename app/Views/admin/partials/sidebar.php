<?php
$uri = service('uri');
$segment1 = $uri->getSegment(1);
$segment2 = $uri->getSegment(2);

?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="<?= site_url('admin/dashboard') ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light">HRC Admin</span>
    </a>

    <div class="sidebar">

        <!-- User Info -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

           <img src="<?= base_url('uploads/' . (session('photo_thumb') ?? 'defaults/avatar.png')) ?>" class="rounded-circle" width="35" height="35" style="object-fit:cover;">
           
            <div class="info">
                <a href="#" class="d-block">
                    <?= esc(session('name')) ?>
                    <small class="text-muted d-block">
                        <?= esc(session('role')) ?>
                    </small>
                </a>
                <a href="#">Division: <?= session('division_name') ?></a>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard') ?>"
                       class="nav-link <?= ($segment2 === 'dashboard') ? 'active' : '' ?>">
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if (session('role_id') == 1 || session('role_id') == 2): ?>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/fixtures') ?>"
                           class="nav-link <?= ($segment2 === 'fixtures') ? 'active' : '' ?>">
                            <p>Fixtures</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/results') ?>"
                           class="nav-link <?= ($segment2 === 'results') ? 'active' : '' ?>">
                            <p>Results</p>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if (session('role_id') == 1): ?>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/pos') ?>"
                           class="nav-link <?= ($segment2 === 'pos') ? 'active' : '' ?>">
                            <p>POS System</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/settings') ?>"
                           class="nav-link <?= ($segment2 === 'settings') ? 'active' : '' ?>">
                            <p>Settings</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/users') ?>"
                           class="nav-link <?= ($segment2 === 'users') ? 'active' : '' ?>">
                            <p>
                                Users
                                <?php if(!empty($pendingCount) && $pendingCount > 0): ?>
                                    <span class="badge bg-danger ms-2">
                                        <?= $pendingCount ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/stock') ?>"
                        class="nav-link <?= ($segment2 === 'stock') ? 'active' : '' ?>">
                        <p>Stock</p>
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </nav>

    </div>
</aside>