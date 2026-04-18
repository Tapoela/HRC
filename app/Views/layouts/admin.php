<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>HRC Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- AdminLTE + Bootstrap + FontAwesome from CDN (reliable, no local path issues) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <!-- Custom Admin Styles (HRC colours, sidebar colours only) -->
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

<!-- jQuery, Bootstrap, AdminLTE JS from CDN -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<?= $this->renderSection('scripts') ?>

<div id="rugbyLoader">
    <div class="pitch"><div class="rugby-ball"></div></div>
    <div class="loaderText">Preparing the pitch...</div>
</div>

<script>
function showLoader(){ document.getElementById("rugbyLoader").style.display="flex"; }
function hideLoader(){ document.getElementById("rugbyLoader").style.display="none"; }

document.querySelectorAll("a").forEach(link => {
  link.addEventListener("click", function(e){
    if (
      this.target !== "_blank" &&
      !this.classList.contains("no-loader") &&
      this.getAttribute("data-widget") !== "pushmenu"
    ) {
      showLoader();
    }
  });
});
</script>

</body>
</html>
