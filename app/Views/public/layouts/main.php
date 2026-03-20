<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= esc($title ?? 'Heidelberg Rugby Club') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
  <link rel="apple-touch-icon" href="<?= base_url('assets/public/images/logo/favicon.png') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/public/images/logo/favicon.png') ?>">
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

  <link rel="stylesheet" href="<?= base_url('assets/public/css/site.css') ?>">
</head>
<body>

<?= $this->include('public/partials/navbar') ?>

<main>
  <?= $this->renderSection('content') ?>
</main>

<?= $this->include('public/partials/footer') ?>

<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/public/js/site.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

</body>
</html>

