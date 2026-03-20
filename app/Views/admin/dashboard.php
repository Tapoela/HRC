<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

<div class="row g-3">

    <!-- 🔶 Pending PO Approvals -->
    <div class="col-12 col-md-6 col-lg-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body d-flex flex-column justify-content-between">

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted mb-0">Pending PO Approvals</h6>
                        <i class="fas fa-file-signature text-warning"></i>
                    </div>

                    <h2 class="fw-bold"><?= $pendingPOCount ?></h2>
                </div>

                <a href="<?= site_url('admin/purchaseorders') ?>"
                   class="btn btn-warning btn-sm w-100 mt-3">
                   View Purchase Orders
                </a>

            </div>

        </div>

    </div>


    <!-- 🔵 Pending Player Approvals -->
    <div class="col-12 col-md-6 col-lg-8">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                <h6 class="mb-0">
                    <i class="fas fa-user-clock text-primary me-2"></i>
                    Pending Player Approvals
                </h6>

                <?php if($pendingCount > 0): ?>
                    <span class="badge bg-primary"><?= $pendingCount ?></span>
                <?php endif; ?>

            </div>

            <div class="card-body p-0">

                <?php if(empty($pendingPlayers)): ?>

                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                        No pending registrations
                    </div>

                <?php else: ?>

                    <!-- MOBILE FRIENDLY LIST -->
                    <div class="list-group list-group-flush">

                        <?php foreach($pendingPlayers as $u): ?>

                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap">

                            <div>
                                <div class="fw-semibold"><?= esc($u['name']) ?></div>
                                <small class="text-muted"><?= esc($u['email']) ?></small>
                            </div>

                            <a href="<?= site_url('admin/users/approve/'.$u['id']) ?>"
                               class="btn btn-success btn-sm mt-2 mt-md-0">
                               <i class="fas fa-check"></i> Approve
                            </a>

                        </div>

                        <?php endforeach ?>

                    </div>

                <?php endif; ?>

            </div>

            <div class="card-footer bg-white text-center border-0">
                <a href="<?= site_url('admin/users') ?>" class="small">
                    View All Users
                </a>
            </div>

        </div>

    </div>

</div>

</div>

<?= $this->endSection() ?>