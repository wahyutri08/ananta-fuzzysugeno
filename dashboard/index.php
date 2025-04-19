<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: ../login");
  exit;
}

$id = $_SESSION["id"];
$role = $_SESSION['role'];
$user = query("SELECT * FROM users WHERE id = $id")[0];

if ($role == 'Admin') {
  $query = query("
    SELECT 
        (SELECT COUNT(*) FROM hasil_fuzzy WHERE keterangan = 'Layak') AS total_layak,
        (SELECT COUNT(*) FROM hasil_fuzzy WHERE keterangan = 'Tidak Layak') AS total_tidak_layak,
        (SELECT COUNT(*) FROM siswa) AS total_siswa
");
} else {
  $query = query("
    SELECT 
        (SELECT COUNT(*) FROM hasil_fuzzy WHERE keterangan = 'Layak' AND user_id = $id) AS total_layak,
        (SELECT COUNT(*) FROM hasil_fuzzy WHERE keterangan = 'Tidak Layak' AND user_id = $id) AS total_tidak_layak,
        (SELECT COUNT(*) FROM siswa WHERE user_id = $id) AS total_siswa
");
}


$totalLayak = $query[0]['total_layak'];
$totalTidakLayak = $query[0]['total_tidak_layak'];
$totalSiswa = $query[0]['total_siswa'];

if ($role == 'Admin') {
  $queryHasil = query("SELECT * FROM hasil_fuzzy");
} else {
  $queryHasil = query("SELECT * FROM hasil_fuzzy WHERE user_id = $id");
}



?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- <link href="../assets/plugins/fontawesome-free/css/fontawesome.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/brands.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/solid.css" rel="stylesheet" /> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <?php require_once '../partials/navbar.php' ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php require_once '../partials/sidebar.php' ?>
    <!-- End Sidebar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= $totalSiswa; ?></h3>

                  <p>Jumlah Siswa</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <span class="small-box-footer">&nbsp;</span>
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $totalLayak; ?></h3>

                  <p>Jumlah Layak Beasiswa</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <span class="small-box-footer">&nbsp;</span>
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3><?= $totalTidakLayak; ?></h3>

                  <p>Jumlah Tidak Layak Beasiswa</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <span class="small-box-footer">&nbsp;</span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="row">
                <div class="col-xl-12 col-lg-12 py-2">
                  <div class="card shadow-sm" style="height : 19rem; background-color: #FFFFFF; background-position: calc(100% + 1rem) bottom; background-size: 30% auto; background-repeat: no-repeat; background-image: url(../assets/dist/img/background/rhone.svg);">
                    <div class=" px-4 mt-4">
                      <h4 class="text-primary"> <b>Hai, <?= $user["nama"]; ?></b> </h4>
                      <h4 class="text-black-50 mb-0">Selamat Datang Di Aplikasi</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary overflow-auto" style="height: 33rem">
                <div class="card-header">
                  <h3 class="card-title">Hasil Beasiswa</h3>
                </div>
                <div class="card-body pt-2">
                  <?php foreach ($queryHasil as $hasil) : ?>
                    <div class="d-flex align-items-center rounded p-2 my-3 bg-gray-light">
                      <span class="info-box-icon bg-success elevation-1 p-3 rounded">
                        <a href="../hasil_fuzzy/cetak_hasil.php?id_hasil=<?= $hasil["id_hasil"]; ?>"><i class="fas fa-book"></i></a>
                      </span>
                      <div class="d-flex flex-column flex-grow-1 mr-2 ml-2">
                        <span class="text-black-50 font-weight-bold">
                          <?= htmlspecialchars($hasil["nama_siswa"], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="font-weight-bolder text-dark py-1 font-size-lg text-right">Hasil Beasiswa:</span>
                        <?php if ($hasil["keterangan"] === 'Layak') : ?>
                          <span class="badge badge-success">
                            <?= htmlspecialchars($hasil["keterangan"], ENT_QUOTES, 'UTF-8'); ?>
                          </span>
                        <?php else : ?>
                          <span class="badge badge-danger">
                            <?= htmlspecialchars($hasil["keterangan"], ENT_QUOTES, 'UTF-8'); ?>
                          </span>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    <!-- Main Footer -->
    <?php require_once '../partials/footer.php' ?>
  </div>
  <!-- /.content-wrapper -->
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="../assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/dist/js/adminlte.min.js"></script>
</body>

</html>