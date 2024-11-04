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

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$users = query("SELECT * FROM users WHERE id = $id");
if (empty($users)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$users = $users[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai yang dikirimkan untuk username baru
    $newUsername = $_POST["username"];

    // Lakukan pemeriksaan dengan database
    $query = "SELECT username FROM users WHERE username = '$newUsername'";
    $result = mysqli_query($db, $query);

    // Jika username yang dikirim sudah ada di database selain username saat ini, tampilkan pesan kesalahan
    if (mysqli_num_rows($result) > 0 && $newUsername !== $users["username"]) {
        echo json_encode(["status" => "error", "message" => "Username sudah ada. Silakan pilih username lain."]);
    } else {
        // Lanjutkan dengan pembaruan data jika tidak ada masalah
        $result = editUsers($_POST);
        if ($result > 0) {

            // Update session data dengan data baru
            $_SESSION['user_data']['username'] = $_POST['username'];
            $_SESSION['user_data']['nama'] = $_POST['nama'];
            $_SESSION['user_data']['email'] = $_POST['email'];
            $_SESSION['user_data']['role'] = $_POST['role'];
            // $_SESSION['user_data']['avatar'] = $_POST['avatar'];

            echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
        } elseif ($result == -1) {
            echo json_encode(["status" => "error", "message" => "Format File Bukan Gambar"]);
        } elseif ($result == -2) {
            echo json_encode(["status" => "error", "message" => "Ukuran Gambar Terlalu Besar"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data Gagal Diubah"]);
        }
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
    <title>Edit User</title>

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
                                <li class="breadcrumb-item">User Management</li>
                                <li class="breadcrumb-item">Edit User</li>
                                <li class="breadcrumb-item active"><?= $users["nama"]; ?></li>
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
                                    <h3 class="card-title">Edit User</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                                    <input type="hidden" name="id" value="<?= $users["id"]; ?>">
                                    <input type="hidden" name="avatarLama" value="<?= $users["avatar"]; ?>">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="username" class="form-control" name="username" id="username" placeholder="Username" value="<?= $users["username"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?= $users["email"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama">Nama <span class="text-danger">*</span></label>
                                                    <input type="nama" class="form-control" name="nama" id="nama" placeholder="Nama" value="<?= $users["nama"]; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password2">Konfirmasi Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" name="password2" id="password2" placeholder="Konfirmasi Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>Role <span class="text-danger">*</span></label>
                                                    <select class="custom-select form-control" id="role" name="role">
                                                        <option value="Admin" <?= ($users["role"] == "Admin") ? "selected" : "" ?>>Admin</option>
                                                        <option value="Staff" <?= ($users["role"] == "Staff") ? "selected" : "" ?>>Staff</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status <span class="text-danger">*</span></label>
                                                    <select class="custom-select form-control" id="status" name="status">
                                                        <option value="Aktif" <?= ($users["status"] == "Aktif") ? "selected" : "" ?>>Aktif</option>
                                                        <option value="Tidak Aktif" <?= ($users["status"] == "Tidak Aktif") ? "selected" : "" ?>>Tidak Aktif</option>
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
                                                <!-- <div class="col-md-7 ml-5">
                                                    <div class="card card-primary card-outline">
                                                        <div class="card-body box-profile" style="height: 180px;">
                                                            <div class="text-center">
                                                                <img class="profile-user-img img-fluid img-circle"
                                                                    src="../assets/dist/img/<?= $users["avatar"]; ?>"
                                                                    style="width: 150px; height: 100px;">
                                                            </div>
                                                            <h3 class="profile-username text-center"><?= $users["nama"]; ?></h3>
                                                            <p class="text-muted text-center"><?= $users["role"]; ?></p>
                                                        </div>
                                                    </div>
                                                </div> -->
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