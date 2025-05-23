<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if ($_SESSION["role"] !== 'Admin') {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = register($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Ditambahkan"]);
    } elseif ($result == -1) {
        echo json_encode(["status" => "error", "message" => "Username Sudah Ada"]);
    } elseif ($result == -2) {
        echo json_encode(["status" => "error", "message" => "Konfirmasi Password Tidak Sesuai"]);
    } elseif ($result == -3) {
        echo json_encode(["status" => "error", "message" => "Your File Not Image"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data Gagal Diubah"]);
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
    <title>Add User</title>

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
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Settings</li>
                                <li class="breadcrumb-item">User Management</li>
                                <li class="breadcrumb-item active">Add User</li>
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
                                    <h3 class="card-title">Add User</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="username">Username <span class="text-danger">*</span></label>
                                            <input type="username" class="form-control" name="username" id="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama <span class="text-danger">*</span></label>
                                            <input type="nama" class="form-control" name="nama" id="nama" placeholder="Nama" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password2">Konfirmasi Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password2" id="password2" placeholder="Konfirmasi Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select class="custom-select form-control" id="role" name="role">
                                                <option>Admin</option>
                                                <option>Staff</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select class="custom-select form-control" id="status" name="status">
                                                <option>Aktif</option>
                                                <option>Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="avatar">Photo Profile</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" name="avatar" id="avatar">
                                                    <label class="custom-file-label" for="avatar">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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
        <!-- /.control-sidebar -->

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
                                window.location.href = '../user_management';
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