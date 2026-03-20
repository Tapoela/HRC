<?= $this->extend('public/layouts/main') ?>
<?= $this->section('content') ?>

<section class="page container">
  <header class="page-header">
    <h2>Upcoming Fixtures</h2>
  </header>

  <?php
  $currentMonth = '';
  $currentDate  = '';
  ?>

  <?php if (!empty($fixtures)): ?>

    <?php foreach ($fixtures as $f): ?>

      <?php
        $monthHeader = date('F Y', strtotime($f['match_date']));
        $dateHeader  = date('d M Y', strtotime($f['match_date']));
      ?>

      <!-- MONTH HEADER -->
      <?php if ($monthHeader !== $currentMonth): ?>
        <?php $currentMonth = $monthHeader; ?>
        <h3 class="fixture-month"><?= strtoupper($currentMonth) ?></h3>
      <?php endif; ?>

      <!-- DATE ROW -->
      <?php if ($dateHeader !== $currentDate): ?>
        <?php $currentDate = $dateHeader; ?>
        <div class="fixture-date-row">
          <strong><?= $currentDate ?></strong>
        </div>
      <?php endif; ?>

      <!-- FIXTURE CARD -->
      <div class="fixture-card">
        <div class="fixture-match">
          <?= esc($f['team']) ?> vs <?= esc($f['opponent']) ?>
        </div>

        <div class="fixture-time">
          <?= !empty($f['match_time']) ? date('H:i', strtotime($f['match_time'])) : 'TBA' ?>
        </div>

        <div class="fixture-venue <?= strtolower($f['venue']) ?>">
          <?= esc($f['venue_name']) ?>
          <small>(<?= esc($f['venue']) ?>)</small>
        </div>
      </div>

    <?php endforeach ?>

  <?php else: ?>

    <p>No upcoming fixtures scheduled.</p>

  <?php endif; ?>


</section>

<?= $this->endSection() ?>
