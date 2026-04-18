<nav class="main-header navbar navbar-expand navbar-white navbar-light">

  <!-- Left: sidebar toggle + home -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= site_url('admin/dashboard') ?>" class="nav-link">Home</a>
    </li>
  </ul>

  <!-- Right: user info + logout -->
  <ul class="navbar-nav ml-auto">
    <?php if (in_array(session('role_id'), [1, 8, 9])): ?>
    <li class="nav-item">
      <a class="nav-link" href="<?= site_url('admin/pos') ?>">
        <i class="fas fa-cash-register"></i>
        <span class="d-none d-sm-inline"> POS</span>
      </a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
      <a class="nav-link" href="/" target="_blank">
        <i class="fas fa-globe"></i>
      </a>
    </li>
    <li class="nav-item">
      <span class="nav-link text-muted">
        <i class="fas fa-user-circle"></i>
        <?= esc(session('name') ?? '') ?>
        <span class="badge badge-secondary ml-1"><?= esc(session('role') ?? '') ?></span>
      </span>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="<?= site_url('logout') ?>">
        <i class="fas fa-sign-out-alt"></i> Logout
      </a>
    </li>
  </ul>

</nav>
