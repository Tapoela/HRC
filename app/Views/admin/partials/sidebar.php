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
                <a href="#"><i class="fas fa-users-cog me-1"></i> Division: 
                    <?= esc(session('division_name') ?: '-') ?>
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= site_url('admin/dashboard') ?>"
                       class="nav-link <?= ($segment2 === 'dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <?php if (session('role_id') == 1 || session('role_id') == 2): ?>

                    <!-- Purchase Orders -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin/purchaseorders') ?>"
                        class="nav-link <?= ($segment2 === 'purchaseorders') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Purchase Orders</p>
                        </a>
                    </li>

                    <!-- Products -->
                    <li class="nav-item">
                        <a href="<?= site_url('admin/products') ?>"
                        class="nav-link <?= ($segment2 === 'products') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Products</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/fixtures') ?>"
                           class="nav-link <?= ($segment2 === 'fixtures') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Fixtures</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/results') ?>"
                           class="nav-link <?= ($segment2 === 'results') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Results</p>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if (in_array(session('role_id'), [1,8,9])): ?>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/pos') ?>"
                           class="nav-link <?= ($segment2 === 'pos') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>POS System</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/settings') ?>"
                           class="nav-link <?= ($segment2 === 'settings') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/users') ?>"
                           class="nav-link <?= ($segment2 === 'users') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
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
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Stock</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= site_url('admin/events') ?>"
                           class="nav-link <?= ($segment2 === 'events') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-calendar"></i>
                            <p>Events Calendar</p>
                        </a>
                    </li>

                <?php endif; ?>

                <?php if (session('role_name') !== 'player'): ?>
                <li class="nav-item">
                    <a href="<?= site_url('admin/teams') ?>" class="nav-link <?= ($segment2 === 'teams') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Team Selections</p>
                    </a>
                </li>
                <!-- Reports Section -->
                <li class="nav-item">
                    <a href="<?= site_url('admin/reports') ?>" class="nav-link <?= ($segment2 === 'reports' && !$uri->getSegment(3)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
</aside>