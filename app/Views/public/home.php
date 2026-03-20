<?= $this->extend('public/layouts/main') ?>

<?= $this->section('content') ?>

<section class="hero">
  <div class="hero-overlay"></div>
  <div class="hero-protea"></div>
  <div class="hero-content">
    <h1>Heidelberg Rugby Club</h1>
    <p>Built on strength. Driven by pride.</p>
    <a class="btn-primary" href="/about">Learn More</a>
  </div>
</section>

<section class="values container">
  <div class="card">Community</div>
  <div class="card">Discipline</div>
  <div class="card">Pride</div>
</section>

<?= $this->endSection() ?>
