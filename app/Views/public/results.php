<?= $this->extend('public/layouts/main') ?>
<?= $this->section('content') ?>

<section class="page container">
  <header class="page-header">
    <h2>Results</h2>
  </header>

  <div class="results">
    <?php if (!empty($results)): ?>
      <div class="results">
        <?php foreach ($results as $r): ?>
          <div class="result">
            <strong><?= esc($r['team']) ?></strong>
            <?= esc($r['team_score']) ?> – <?= esc($r['opponent_score']) ?>
            <strong><?= esc($r['opponent']) ?></strong>
          </div>
        <?php endforeach ?>
      </div>
    <?php else: ?>
      <p>No results yet.</p>
    <?php endif ?>
  </div>
</section>

<?= $this->endSection() ?>
