<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Heidelberg Rugby Club') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/public/css/site.css') ?>">
</head>
<body>
    <?= $this->include('public/partials/navbar') ?>
    <main>
        <?= $this->renderSection('content') ?>
    </main>
    <?= $this->include('public/partials/footer') ?>
</body>
</html>
