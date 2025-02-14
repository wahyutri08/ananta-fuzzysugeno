<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];


if (isset($_GET["id_siswa"]) && is_numeric($_GET["id_siswa"])) {
    $id_siswa = $_GET["id_siswa"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}


if ($role == 'Admin') {
    $siswa = query("SELECT * FROM siswa WHERE id_siswa = $id_siswa");
} else {
    $siswa = query("SELECT * FROM siswa WHERE id_siswa = $id_siswa AND user_id = $user_id");
}


if (empty($siswa)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$siswa = $siswa[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = editSiswa($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
    } elseif ($result == -1) {
        echo json_encode(["status" => "error", "message" => "NIS Sudah Ada Sebelumnya"]);
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
    <title>Edit Siswa</title>

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
                                <li class="breadcrumb-item">Data Siswa</li>
                                <li class="breadcrumb-item">Edit</li>
                                <li class="breadcrumb-item active"><?= $siswa["nama_siswa"]; ?></li>
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
                                    <h3 class="card-title">Tambah Data</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <input type="hidden" name="id_siswa" value="<?= $siswa["id_siswa"]; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="username">NIS <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="nis" id="nis" placeholder="NIS" value="<?= $siswa["nis"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama_siswa">Nama Siswa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" placeholder="Nama Siswa" value="<?= $siswa["nama_siswa"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" value="<?= $siswa["alamat"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" value="<?= $siswa["tanggal_lahir"]; ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="kelas">Kelas <span class="text-danger">*</span></label>
                                                    <input type="kelas" class="form-control" name="kelas" id="kelas" placeholder="Kelas" value="<?= $siswa["kelas"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                                    <br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="Laki-Laki" value="Laki-Laki" <?= $siswa["jenis_kelamin"] == 'Laki-Laki' ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="Laki-Laki">Laki-laki</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_kelamin" id="Perempuan" value="Perempuan" <?= $siswa["jenis_kelamin"] == 'Perempuan' ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="Perempuan">Perempuan</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="no_telfon">No Telepon <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="no_telfon" id="no_telfon" placeholder="No Telepon" value="<?= $siswa["no_telfon"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= $siswa["email"]; ?>" required>
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
                                window.location.href = '../data_siswa';
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