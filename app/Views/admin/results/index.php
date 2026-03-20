<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">Results</h1>

<a href="/admin/results/create" class="btn btn-primary mb-3">
  <i class="fas fa-plus"></i> Add Result
</a>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>Match</th>
      <th>Score</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($results as $r): ?>
      <tr>
        <td><?= esc($r['match_date']) ?></td>
        <td><?= esc($r['team']) ?> vs <?= esc($r['opponent']) ?></td>
        <td><?= esc($r['team_score']) ?> - <?= esc($r['opponent_score']) ?></td>
        <td width="80">
          <a href="/admin/results/delete/<?= $r['id'] ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Delete this result?')">
            <i class="fas fa-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<?= $this->endSection() ?>
