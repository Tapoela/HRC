<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="mb-3">
  <i class="fas fa-users"></i> Add Player
</h1>

<form method="post" action="/admin/users/store" enctype="multipart/form-data">

<div class="card card-primary card-outline">

<!-- TAB HEADER -->
<div class="card-header p-0 border-bottom-0">
<ul class="nav nav-tabs" id="playerTabs" role="tablist">

<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#general">
<i class="fas fa-user"></i> General
</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#contact">
<i class="fas fa-phone"></i> Contact
</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#rugby">
<i class="fas fa-football-ball"></i> Rugby
</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#login">
<i class="fas fa-lock"></i> Login
</a>
</li>

<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#docs">
<i class="fas fa-file"></i> Documents
</a>
</li>

</ul>
</div>

<!-- TAB CONTENT -->
<div class="card-body">
<div class="tab-content">

<!-- ================= GENERAL TAB ================= -->
<div class="tab-pane fade show active" id="general">

<div class="row">

<div class="col-md-4">
<label>Full Name</label>
<input name="fname" class="form-control">
</div>

<div class="col-md-4">
<label>Surname</label>
<input name="surname" class="form-control">
</div>

<div class="col-md-4">
<label>ID Number</label>
<input id="idnumber" name="idnumber" class="form-control">
<small id="validationResult" class="text-danger"></small>
</div>

<div class="col-md-4 mt-3">
<label>Gender</label>
<select name="gender" class="form-control">
<option value="">Select</option>
<option>Male</option>
<option>Female</option>
</select>
</div>

<div class="col-md-4 mt-3">
<label>Birth Date</label>
<input type="date" name="birthdate" class="form-control">
</div>

</div>
</div>

<!-- ================= CONTACT TAB ================= -->
<div class="tab-pane fade" id="contact">

<div class="row">

<div class="col-md-4">
<label>Email</label>
<input name="email" class="form-control">
</div>

<div class="col-md-4">
<label>Telephone</label>
<input name="telephone" class="form-control">
</div>

<div class="col-md-4">
<label>Spouse Name</label>
<input name="spousename" class="form-control">
</div>

<div class="col-md-4 mt-3">
<label>Spouse Tel</label>
<input name="spouseTelNo" class="form-control">
</div>

<div class="col-md-8 mt-3">
<label>Address</label>
<input name="address" class="form-control">
</div>

</div>
</div>

<!-- ================= RUGBY TAB ================= -->
<div class="tab-pane fade" id="rugby">

<div class="row">

<div class="col-md-4">
<label>Team</label>
<select name="team" class="form-control">
<?php foreach($Teams ?? [] as $ts): ?>
<option value="<?= $ts->TeamId ?>"><?= $ts->TeamName ?></option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-4">
<label>Club</label>
<select name="club" class="form-control">
<?php foreach($Club ?? [] as $c): ?>
<option value="<?= $c->ClubId ?>">
<?= $c->Name ?> - <?= $c->Abbr ?>
</option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-4">
<label>Position</label>
<select name="position" class="form-control">
<?php foreach($Position ?? [] as $p): ?>
<option value="<?= $p->PosId ?>">
<?= $p->PosId ?> - <?= $p->PosistionName ?>
</option>
<?php endforeach ?>
</select>
</div>

<div class="col-md-2 mt-3">
<label>Height</label>
<input name="height" class="form-control">
</div>

<div class="col-md-2 mt-3">
<label>Weight</label>
<input name="weight" class="form-control">
</div>

</div>
</div>

<!-- ================= LOGIN TAB ================= -->
<div class="tab-pane fade" id="login">

<div class="row">

<div class="col-md-4">
<label>Password</label>
<input type="password" name="password" class="form-control">
</div>

<div class="col-md-4">
<label>Confirm Password</label>
<input type="password" name="cpassword" class="form-control">
</div>

<div class="col-md-4">
<label>Role</label>
<select name="role_id" class="form-control">
<?php foreach($roles as $r): ?>
<option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
<?php endforeach ?>
</select>
</div>

</div>
</div>

<!-- ================= DOCUMENTS TAB ================= -->
<div class="tab-pane fade" id="docs">

<label>Upload Files</label>
<input type="file" name="userfile[]" multiple class="form-control">

</div>

</div>
</div>

<div class="card-footer">
<button class="btn btn-success">
<i class="fas fa-save"></i> Save Player
</button>

<a href="/admin/users" class="btn btn-danger">Cancel</a>
</div>

</div>
</form>

<?= $this->endSection() ?>
