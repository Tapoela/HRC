<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<h2>Player Dashboard</h2>

<p>Welcome <?= esc($user->name) ?></p>

<div class="card mt-3">
    <h3>Membership Card</h3>

    <?= view('components/user_card', ['user'=>$user,'qr'=>$qr]) ?>
</div>

<?= $this->endSection() ?>