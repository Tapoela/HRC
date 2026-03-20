<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1>Coach Dashboard</h1>

	<?= view('components/user_card', [
	        'user' => $user,
	        'role' => 'coach'
	]) ?>

<?= $this->endSection() ?>
