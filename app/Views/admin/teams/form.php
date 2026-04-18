<?php
// Admin Team Selection Create/Edit Form
$this->extend('layouts/admin');
$this->section('content');
?>
<div class="container-fluid">
    <h1 class="mb-4"><?= isset($team) ? 'Edit' : 'Create' ?> Team Selection</h1>
    <div class="mb-3">
        <a href="<?= site_url('admin/teams') ?>" class="btn btn-secondary">Back to Team List</a>
    </div>
    <?php if (session('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if (isset($debug)): ?>
        <div class="alert alert-warning">
            <strong>Debug Info:</strong>
            <pre><?= print_r($debug, true) ?></pre>
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="post" action="<?php if (isset($team['id'])): ?>
                <?= site_url('admin/teams/edit/' . encode_id($team['id'])) ?>
            <?php else: ?>
                <?= site_url('admin/teams/create/' . encode_id($team['fixture_id'] ?? $fixture_id ?? '')) ?>
            <?php endif; ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="team_name" class="form-label">Team Name</label>
                    <input type="text" class="form-control" id="team_name" value="<?= old('team_name', $team['team_name'] ?? $defaultTeamName ?? '') ?>" required readonly>
                    <input type="hidden" name="team_name" value="<?= old('team_name', $team['team_name'] ?? $defaultTeamName ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label for="fixture_id" class="form-label">Fixture</label>
                    <select class="form-select" id="fixture_id_display" required readonly tabindex="-1">
                        <?php foreach ($fixtures as $fixture): ?>
                            <option value="<?= $fixture['id'] ?>" <?= old('fixture_id', $team['fixture_id'] ?? $fixture_id ?? '') == $fixture['id'] ? 'selected' : '' ?>>
                                <?= esc($fixture['team']) ?> vs <?= esc($fixture['opponent']) ?> (<?= date('Y-m-d', strtotime($fixture['match_date'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="fixture_id" value="<?= old('fixture_id', $team['fixture_id'] ?? $fixture_id ?? '') ?>">
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="coach1" class="form-label">Coach 1</label>
                        <select class="form-select" name="coach1" id="coach1" required>
                            <option value="">Select Coach</option>
                            <?php if (isset($coaches)): foreach ($coaches as $coach): ?>
                                <option value="<?= $coach['id'] ?>" <?= (old('coach1', $team['coach1_id'] ?? '') == $coach['id']) ? 'selected' : '' ?>>
                                    <?= esc($coach['name']) ?> <?= esc($coach['surname'] ?? '') ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="coach2" class="form-label">Coach 2</label>
                        <select class="form-select" name="coach2" id="coach2">
                            <option value="">Select Coach</option>
                            <?php if (isset($coaches)): foreach ($coaches as $coach): ?>
                                <option value="<?= $coach['id'] ?>" <?= (old('coach2', $team['coach2_id'] ?? '') == $coach['id']) ? 'selected' : '' ?>>
                                    <?= esc($coach['name']) ?> <?= esc($coach['surname'] ?? '') ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-6">
                        <label for="manager" class="form-label">Team Manager</label>
                        <select class="form-select" name="manager" id="manager">
                            <option value="">Select Manager</option>
                            <?php if (isset($managers)): foreach ($managers as $manager): ?>
                                <option value="<?= $manager['id'] ?>" <?= (old('manager', $team['manager_id'] ?? '') == $manager['id']) ? 'selected' : '' ?>>
                                    <?= esc($manager['name']) ?> <?= esc($manager['surname'] ?? '') ?>
                                </option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Players for Each Position</label>
                    <div class="row">
                        <?php foreach ($positions as $i => $position): ?>
                        <div class="col-md-6 mb-2">
                            <label class="form-label"><?= esc($position) ?></label>
                            <select class="form-select player-select" name="players[<?= $i ?>]" data-position="<?= $i ?>" required>
                                <option value="">Select Player</option>
                                <?php foreach ($players as $player): ?>
                                    <option value="<?= $player['id'] ?>" <?= (old('players.' . $i, $team['players'][$i] ?? '') == $player['id']) ? 'selected' : '' ?>>
                                        <?= esc($player['name']) ?> <?= esc($player['surname'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes (optional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes', $team['notes'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">Save Team</button>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
// Disable already selected players in other dropdowns
function updatePlayerDropdowns() {
    const selects = document.querySelectorAll('.player-select');
    const selected = Array.from(selects).map(s => s.value).filter(v => v);
    selects.forEach(sel => {
        Array.from(sel.options).forEach(opt => {
            if (opt.value && selected.includes(opt.value) && sel.value !== opt.value) {
                opt.disabled = true;
            } else {
                opt.disabled = false;
            }
        });
    });
}
document.querySelectorAll('.player-select').forEach(sel => {
    sel.addEventListener('change', updatePlayerDropdowns);
});
// Run on page load
window.addEventListener('DOMContentLoaded', updatePlayerDropdowns);
</script>
<?php $this->endSection(); ?>
