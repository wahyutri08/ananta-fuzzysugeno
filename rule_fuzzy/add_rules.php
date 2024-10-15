<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$variabel = query("SELECT * FROM variabel");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = addRules($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data Gagal Ditambahkan"]);
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
    <title>Add Rules</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
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
                            <!-- <h1 class="m-0">Add User</h1> -->
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item">Rule Fuzzy</li>
                                <li class="breadcrumb-item active">Tambah Rule Fuzzy</li>
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
                                    <h3 class="card-title">Tambah Rule Fuzzy</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <?php foreach ($variabel as $v) : ?>
                                                    <div class="form-group">
                                                        <label><?= $v["nama_variabel"]; ?> <span class="text-danger">*</span></label>
                                                        <br>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="nilai_<?= $v["id_variabel"]; ?>" id="Rendah_<?= $v['id_variabel']; ?>" value=" Rendah">
                                                            <label class="form-check-label" for="Rendah_<?= $v['id_variabel']; ?>">Rendah</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="nilai_<?= $v['id_variabel']; ?>" id="Sedang_<?= $v['id_variabel']; ?>" value="Sedang">
                                                            <label class="form-check-label" for="Sedang_<?= $v['id_variabel']; ?>">Sedang</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="nilai_<?= $v['id_variabel']; ?>" id="Tinggi_<?= $v['id_variabel']; ?>" value="Tinggi">
                                                            <label class="form-check-label" for="Tinggi_<?= $v['id_variabel']; ?>">Tinggi</label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <div class="form-group">
                                                    <label for="nilai">Nilai <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="nilai" id="nilai" placeholder="Nilai" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
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
    <!-- bs-custom-file-input -->
    <script src="../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <!-- Sweetalert -->
    <script src="../plugins/sweetalert/sweetalert2.all.min.js"></script>
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