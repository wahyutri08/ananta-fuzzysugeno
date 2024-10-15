<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../dashboard");
    exit;
}


$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM rule_fuzzy"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) ? (int)$_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$rule_fuzzy = query("SELECT * FROM rule_fuzzy LIMIT $awalData, $jumlahDataPerHalaman");

$variabel = query("SELECT * FROM variabel");

// if (isset($_POST["search"])) {
//     $d_siswa = searchSiswa($_POST["keyword"]);
// }

// if ($halamanAktif > $jumlahHalaman) {
//     header("Location: ../data_siswa");
//     exit();
// }
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
    <title>Rule Fuzzy</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- <link href="../plugins/fontawesome-free/css/fontawesome.css" rel="stylesheet" />
  <link href="../plugins/fontawesome-free/css/brands.css" rel="stylesheet" />
  <link href="../plugins/fontawesome-free/css/solid.css" rel="stylesheet" /> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
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
                            <h1 class="m-0">Data Variabel</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Data Variabel</li>
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
                        <div class="col">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><a href="add_rules.php" class="btn btn-sm btn-block bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a></h3>
                                    <div class="card-tools mt-2">
                                        <form action="" method="POST">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" id="keyword" name="keyword" class="form-control float-right" placeholder="Search">

                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default" disabled>
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0" id="tabel">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No</th>
                                                <?php foreach ($variabel as $v) : ?>
                                                    <th><?= $v["nama_variabel"]; ?></th>
                                                <?php endforeach; ?>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 1; ?>
                                            <?php foreach ($rule_fuzzy as $f) : ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <td><?= $f["nilai_uts"]; ?></td>
                                                    <td><?= $f["nilai_uas"]; ?></td>
                                                    <td><?= $f["nilai_keaktifan"]; ?></td>
                                                    <td><?= $f["nilai_penghasilan"]; ?></td>
                                                    <td><?= $f["nilai"]; ?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item" href="edit_rule.php?id_rule=<?= $f["id_rule"]; ?>">Edit</a></li>
                                                                <li><a class="dropdown-item tombol-hapus" href="delete_rule.php?id_rule=<?= $f["id_rule"]; ?>">Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $n++; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php' ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Sweetalert -->
    <script src="../plugins/sweetalert/sweetalert2.all.min.js"></script>
</body>

</html>