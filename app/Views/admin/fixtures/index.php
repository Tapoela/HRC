<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">Fixtures</h1>

<a href="/admin/fixtures/create" class="btn btn-primary mb-3">
  <i class="fas fa-plus"></i> Add Fixture
</a>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>Match</th>
      <th>Venue</th>
      <th>Match Time</th>
      <th>Location</th>
      <th></th>
    </tr>
  </thead>
    <tbody>
      <?php foreach ($fixtures as $f): ?>
        <tr>
          <td><?= esc($f['match_date']) ?></td>
          <td><?= esc($f['team']) ?> vs <?= esc($f['opponent']) ?></td>
          <td><?= esc($f['venue']) ?></td>
          <td><?= esc($f['match_time']) ?></td>
          <td><?= esc($f['venue_name']) ?></td>
          <td width="120">

            <button
              class="btn btn-warning btn-sm editFixtureBtn"
              data-id="<?= $f['id'] ?>"
              data-team="<?= esc($f['team']) ?>"
              data-opponent="<?= esc($f['opponent']) ?>"
              data-date="<?= esc($f['match_date']) ?>"
              data-time="<?= esc($f['match_time']) ?>"
              data-venue="<?= esc($f['venue']) ?>"
              data-venuename="<?= esc($f['venue_name']) ?>"
            >
              <i class="fas fa-edit"></i>
            </button>

            <a href="/admin/fixtures/delete/<?= $f['id'] ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete this fixture?')">
              <i class="fas fa-trash"></i>
            </a>

          </td>

        </tr>
      <?php endforeach ?>
    </tbody>
</table>

<!-- 🔥 MOVE MODAL HERE (INSIDE SECTION) -->
<div class="modal fade" id="editFixtureModal">
  <div class="modal-dialog">
    <form method="post" action="/admin/fixtures/update">
      <div class="modal-content">

        <div class="modal-header bg-warning">
          <h5 class="modal-title">Edit Fixture</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="id" id="edit_id">

          <div class="form-group">
            <label>Team</label>
            <input name="team" id="edit_team" class="form-control">
          </div>

          <div class="form-group">
            <label>Opponent</label>
            <input name="opponent" id="edit_opponent" class="form-control">
          </div>

          <div class="form-group">
            <label>Date</label>
            <input type="date" name="match_date" id="edit_date" class="form-control">
          </div>

          <div class="form-group">
            <label>Time</label>
            <input type="time" name="match_time" id="edit_time" class="form-control">
          </div>

          <div class="form-group">
            <label>Venue Type</label>
            <select name="venue" id="edit_venue" class="form-control">
              <option value="Home">Home</option>
              <option value="Away">Away</option>
            </select>
          </div>

          <div class="form-group">
            <label>Venue Name</label>
            <input name="venue_name" id="edit_venuename" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-success">Update Fixture</button>
        </div>

      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
$(function(){

  $('.editFixtureBtn').on('click', function(){

    $('#edit_id').val($(this).data('id'));
    $('#edit_team').val($(this).data('team'));
    $('#edit_opponent').val($(this).data('opponent'));
    $('#edit_date').val($(this).data('date'));
    $('#edit_time').val($(this).data('time'));
    $('#edit_venue').val($(this).data('venue'));
    $('#edit_venuename').val($(this).data('venuename'));

    $('#editFixtureModal').modal('show');

  });

});
</script>
<?= $this->endSection() ?>
