<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">Add Fixture</h1>

<form method="post" action="/admin/fixtures/store">

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
    <div class="col-md-4 form-group">
      <label>Date</label>
      <input type="date" name="match_date" class="form-control" required>
    </div>

    <div class="col-md-4 form-group">
      <label>Kickoff Time</label>
      <input type="time" name="match_time" class="form-control">
    </div>

    <div class="col-md-4 form-group">
      <label>Venue Type</label>
      <select name="venue" class="form-control">
        <option value="Home">Home</option>
        <option value="Away">Away</option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label>Venue Name (Public Display)</label>
    <input name="venue_name" class="form-control" placeholder="Alberton Rugby Club">
  </div>

  <button class="btn btn-success mt-3">
    <i class="fas fa-save"></i> Save Fixture
  </button>

</form>


<?= $this->endSection() ?>
