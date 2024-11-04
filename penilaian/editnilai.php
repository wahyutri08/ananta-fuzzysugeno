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

$variabel = query("SELECT * FROM variabel");

if (empty($siswa)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$siswa = $siswa[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pengecekan apakah ada data yang diinput
    if (empty($_POST)) {
        echo json_encode(["status" => "error", "message" => "Tidak ada data yang diinput"]);
        exit;
    }

    // Memanggil fungsi jika ada data yang diinput
    dataPostNilai($_POST, $_GET, $role, $user_id);
    echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
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
    <title>Edit Nilai</title>

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
                            <h1 class="m-0">Edit Nilai</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
                                <li class="breadcrumb-item">Penilaian</li>
                                <li class="breadcrumb-item">Edit Nilai</li>
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
                                    <h3 class="card-title">Edit Nilai</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <input type="hidden" name="id_siswa" value="<?= $siswa["id_siswa"]; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <?php foreach ($variabel as $v) : ?>
                                                    <div class="form-group row">
                                                        <label for="<?= $v["id_variabel"]; ?>" class="col-sm-5 control-label"><?= $v["nama_variabel"]; ?> <span class="text-danger">*</span></label>
                                                        <div class="col-sm-6">
                                                            <?php
                                                            // Cek role, ambil nilai hanya jika user adalah admin atau terkait sebagai staff
                                                            if ($role == 'Admin') {
                                                                $penilaian = query("SELECT * FROM penilaian WHERE id_siswa = " . $siswa['id_siswa'] . " AND id_variabel = " . $v['id_variabel']);
                                                            } elseif ($role == 'Staff') {
                                                                $penilaian = query("SELECT * FROM penilaian WHERE id_siswa = " . $siswa['id_siswa'] . " AND id_variabel = " . $v['id_variabel'] . " AND user_id = " . $user_id);
                                                            }

                                                            if ($penilaian) {
                                                                echo '<input type="number" class="form-control" name="' . $v['id_variabel'] . '" id="' . $v['id_variabel'] . '" placeholder="Nilai" value="' . $penilaian[0]['nilai'] . '">';
                                                            } else {
                                                                echo '<input type="number" class="form-control" name="' . $v['id_variabel'] . '" id="' . $v['id_variabel'] . '" placeholder="Nilai" value="">';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
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
                                window.location.href = '../penilaian';
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