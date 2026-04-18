<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1>Coach Dashboard</h1>

<?= view('components/user_card', [
    'user' => (object)[
        'name' => session('name'),
        'role_name' => session('role_name'),
        'photo' => session('photo_thumb') ?? 'defaults/avatar.png',
        'team' => session('team') ?? '',
        'license' => session('license') ?? '',
        'position' => session('position') ?? '',
        'division_name' => session('division_name') ?? '',
    ],
    'role' => 'coach'
]) ?>

<?= $this->endSection() ?>
