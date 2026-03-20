<form method="post" action="<?= site_url('admin/settings/save') ?>">

<div class="card">
<div class="card-header">Registration Email Routing</div>
<div class="card-body">

<div class="form-group">
<label>Junior Department Email</label>
<input name="junior_email" value="<?= esc($junior_email) ?>" class="form-control">
</div>

<div class="form-group">
<label>Senior Department Email</label>
<input name="senior_email" value="<?= esc($senior_email) ?>" class="form-control">
</div>

</div>

<div class="card-footer">
<button class="btn btn-success">Save Settings</button>
</div>

</div>

</form>
