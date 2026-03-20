<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">Add Result</h1>

<form method="post" action="/admin/results/store">
  <div class="row">
    <div class="col-md-6 form-group">
      <label>Team</label>
      <input name="team" class="form-control" required>
    </div>

    <div class="col-md-6 form-group">
      <label>Opponent</label>
      <input name="opponent" class="form-control" required>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 form-group">
      <label>Team Score</label>
      <input type="number" name="team_score" class="form-control" required>
    </div>

    <div class="col-md-3 form-group">
      <label>Opponent Score</label>
      <input type="number" name="opponent_score" class="form-control" required>
    </div>

    <div class="col-md-6 form-group">
      <label>Match Date</label>
      <input type="date" name="match_date" class="form-control" required>
    </div>
  </div>

  <button class="btn btn-success mt-3">
    <i class="fas fa-save"></i> Save
  </button>
</form>

<?= $this->endSection() ?>
