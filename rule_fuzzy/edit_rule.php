<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

if (isset($_GET["id_rule"]) && is_numeric($_GET["id_rule"])) {
    $id_rule = $_GET["id_rule"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$rule_fuzzy = query("SELECT * FROM rule_fuzzy WHERE id_rule = $id_rule");

if (empty($rule_fuzzy)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$rule_fuzzy = $rule_fuzzy[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = editRule($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Rule Sudah Ada Sebelumnya"]);
    }
    exit;
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
    <title>Edit Rules</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
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
                            <!-- <h1 class="m-0">Add User</h1> -->
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item">Rule Fuzzy</li>
                                <li class="breadcrumb-item active">Edit</li>
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
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Edit Rule Fuzzy</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <input type="hidden" name="id_rule" value="<?= $rule_fuzzy["id_rule"]; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="nilai_uts">Nilai Ujian Tengeh Semester <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="nilai_uts" id="nilai_uts" required>
                                                        <option value="" disabled selected>-Choose One-</option>
                                                        <option value="Rendah" <?= ($rule_fuzzy["nilai_uts"] == "Rendah") ? "selected" : "" ?>>Rendah</option>
                                                        <option value="Sedang" <?= ($rule_fuzzy["nilai_uts"] == "Sedang") ? "selected" : "" ?>>Sedang</option>
                                                        <option value="Tinggi" <?= ($rule_fuzzy["nilai_uts"] == "Tinggi") ? "selected" : "" ?>>Tinggi</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nilai_uas">Nilai Ujian Akhir Semester <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="nilai_uas" id="nilai_uas" required>
                                                        <option value="" disabled selected>-Choose One-</option>
                                                        <option value="Rendah" <?= ($rule_fuzzy["nilai_uas"] == "Rendah") ? "selected" : "" ?>>Rendah</option>
                                                        <option value="Sedang" <?= ($rule_fuzzy["nilai_uas"] == "Sedang") ? "selected" : "" ?>>Sedang</option>
                                                        <option value="Tinggi" <?= ($rule_fuzzy["nilai_uas"] == "Tinggi") ? "selected" : "" ?>>Tinggi</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nilai_keaktifan">Nilai Keaktifan <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="nilai_keaktifan" id="nilai_keaktifan" required>
                                                        <option value="" disabled selected>-Choose One-</option>
                                                        <option value="Rendah" <?= ($rule_fuzzy["nilai_keaktifan"] == "Rendah") ? "selected" : "" ?>>Rendah</option>
                                                        <option value="Sedang" <?= ($rule_fuzzy["nilai_keaktifan"] == "Sedang") ? "selected" : "" ?>>Sedang</option>
                                                        <option value="Tinggi" <?= ($rule_fuzzy["nilai_keaktifan"] == "Tinggi") ? "selected" : "" ?>>Tinggi</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="nilai_penghasilan">Nilai Penghasilan <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="nilai_penghasilan" id="nilai_penghasilan" required>
                                                        <option value="" disabled selected>-Choose One-</option>
                                                        <option value="Rendah" <?= ($rule_fuzzy["nilai_penghasilan"] == "Rendah") ? "selected" : "" ?>>Rendah</option>
                                                        <option value="Sedang" <?= ($rule_fuzzy["nilai_penghasilan"] == "Sedang") ? "selected" : "" ?>>Sedang</option>
                                                        <option value="Tinggi" <?= ($rule_fuzzy["nilai_penghasilan"] == "Tinggi") ? "selected" : "" ?>>Tinggi</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nilai">Nilai <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="nilai" id="nilai" value="<?= $rule_fuzzy["nilai"]; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-success">Submit Change</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php' ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- Sweetalert -->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: res.message,
                                icon: "success"
                            }).then(() => {
                                window.location.href = '../rule_fuzzy';
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>