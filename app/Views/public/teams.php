<?= $this->extend('public/layouts/main') ?>
<?= $this->section('content') ?>

<section class="page container">
  <header class="page-header">
    <h2>Our Teams</h2>
  </header>

  <div class="grid teams">
    <article class="team-card">
      <h3>Senior Men</h3>
      <p>Competitive first-team rugby.</p>
    </article>
    <article class="team-card">
      <h3>Juniors</h3>
      <p>Developing future champions.</p>
    </article>
  </div>
</section>

<?= $this->endSection() ?>
