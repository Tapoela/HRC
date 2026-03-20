<?= $this->extend('layouts/admin') ?>



    <div class="card-body text-center">

        <img src="<?= base_url('uploads/'.$user->photo) ?>"
             class="rounded-circle mb-3"
             width="110">

        <h5><?= esc($user->name) ?></h5>

        <p class="mb-1">
            <strong>Role:</strong> <?= ucfirst($user->role_name ?? '-') ?>
        </p>

        <?php if (session('role_id') == 3): ?>

            <p>
                <strong>Position:</strong> <?= esc($user->position ?? '-') ?><br>
                <strong>Division:</strong> <?= esc($user->division_name ?? '-') ?>
            </p>

        <?php elseif (session('role_id') == 2): ?>

            <p>
                <strong>Team:</strong> <?= esc($user->team) ?><br>
                <strong>License:</strong> <?= esc($user->license) ?>
            </p>

        <?php endif; ?>

    </div>

