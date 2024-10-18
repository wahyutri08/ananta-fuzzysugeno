<?php
session_start();
require_once '../functions.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cegah akses ke halaman ini jika pengguna sudah login
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: ../dashboard");
    exit;
}

$error = '';

if (isset($_POST["login"])) {
    $usernameOremail = $_POST["username"];
    $password = $_POST["password"];

    // Query untuk mencari pengguna berdasarkan username atau email
    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$usernameOremail' OR email = '$usernameOremail'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Cek status pengguna
        if ($row['status'] === 'Aktif') {
            // Verifikasi password
            if (password_verify($password, $row["password"])) {
                // Jika login berhasil, set session
                $_SESSION["login"] = true;
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['avatar'] = $row['avatar'];
                $_SESSION['role'] = $row['role'];
                header("Location: ../dashboard");
                exit;
            } else {
                $error = 'Password Salah.';
            }
        } else {
            $error = 'Akun Anda Tidak Aktif. Silakan Hubungi Admin.';
        }
    } else {
        $error = 'Username atau Email tidak ditemukan.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../login" class="h1"><b>PUSING</b></a>
            </div>
            <div class="card-body">
                <?php if ($error) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <!-- <p class="login-box-msg h3">LOGIN</p> -->

                <form action="" method="POST">
                    <div class="input-group mb-3">
                        <input type="username" id="username" name="username" class="form-control" placeholder="Username atau Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" name="login" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
</body>

</html>