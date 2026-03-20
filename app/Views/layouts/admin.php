<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    /* ===== FIX ADMINLTE WHITE TEXT IN MODAL TABS ===== */

    #editUserModal .nav-tabs .nav-link {
        color: #495057 !important;
    }

    #editUserModal .nav-tabs .nav-link.active {
        color: #007bff !important;
        background-color: #fff !important;
    }

    #editUserModal label,
    #editUserModal .form-group,
    #editUserModal .form-control {
        color: #212529 !important;
    }
  </style>

  <meta charset="utf-8">
  <title>HRC Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">

  <!-- Custom Admin Styles -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/custom.css') ?>">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?= $this->include('admin/partials/navbar') ?>

  <!-- Sidebar -->
  <?= $this->include('admin/partials/sidebar') ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content pt-3">
      <div class="container-fluid">
        <?= $this->renderSection('content') ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <?= $this->include('admin/partials/footer') ?>

</div>

<!-- jQuery -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>

<!-- Bootstrap -->
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- AdminLTE -->
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>

<div id="rugbyLoader">

    <div class="pitch">

        <div class="rugby-ball"></div>

    </div>

    <div class="loaderText">
        Preparing the pitch...
    </div>

</div>

<script>
function showLoader(){

document.getElementById("rugbyLoader").style.display="flex";

}

function hideLoader(){

document.getElementById("rugbyLoader").style.display="none";

}

document.querySelectorAll("a").forEach(link => {

link.addEventListener("click", function(e){

if(this.target !== "_blank" && !this.classList.contains("no-loader")){
showLoader();
}

});

});
</script>

</body>
</html>
