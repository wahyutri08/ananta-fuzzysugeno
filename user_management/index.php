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
$jumlahData = count(query("SELECT * FROM users"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) ? (int)$_GET["page"] : 1;
$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

$users = query("SELECT * FROM users LIMIT $awalData, $jumlahDataPerHalaman");
if (isset($_POST["search"])) {
    $users = searchUsers($_POST["keyword"]);
}

if ($halamanAktif > $jumlahHalaman) {
    header("Location: ../user_management");
    exit();
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
    <title>User Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
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
                            <h1 class="m-0">User Management</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">User Management</li>
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
                                    <h3 class="card-title"><a href="../register" class="btn btn-sm btn-block bg-gradient-primary"><i class="fas fa-user-plus"></i> Add User</a></h3>
                                    <div class="card-tools mt-2">
                                        <form action="" method="POST">
                                            <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" id="keyword" name="keyword" class="form-control float-right" placeholder="Search">

                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default">
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
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Nama</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 1; ?>
                                            <?php foreach ($users as $userData) : ?>
                                                <tr>
                                                    <td><?= $n; ?></td>
                                                    <!-- <td><?= $userData["id"]; ?></td> -->
                                                    <td><?= $userData["username"]; ?></td>
                                                    <td><?= $userData["nama"]; ?></td>
                                                    <td><?= $userData["email"]; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($userData['role'] == 'Admin') {
                                                            echo '<span class="badge bg-success">' . $userData["role"] . '</span>';
                                                        } else {
                                                            echo '<span class="badge bg-warning">' . $userData["role"] . '</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        if ($userData['status'] == 'Aktif') {
                                                            echo '<span class="badge bg-success">' . $userData["status"] . '</span>';
                                                        } else {
                                                            echo '<span class="badge bg-danger">' . $userData["status"] . '</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item" href="edit_users.php?id=<?= $userData["id"]; ?>">Edit</a></li>
                                                                <li><a class="dropdown-item tombol-hapus" href="delete_users.php?id=<?= $userData["id"]; ?>">Delete</a></li>
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
                                <div class="card-footer clearfix">
                                    <ul class="pagination pagination-sm m-0 float-right">
                                        <li class="page-item"><a class="page-link" href="?page=<?= max(1, $halamanAktif - 1); ?>">Previous</a></li>
                                        <?php
                                        // Batasi jumlah maksimum item navigasi menjadi 5
                                        $jumlahTampil = min(5, $jumlahHalaman);
                                        // Hitung titik awal iterasi untuk tetap berada di tengah
                                        $start = max(1, min($halamanAktif - floor($jumlahTampil / 2), $jumlahHalaman - $jumlahTampil + 1));
                                        // Hitung titik akhir iterasi
                                        $end = min($start + $jumlahTampil - 1, $jumlahHalaman);

                                        for ($i = $start; $i <= $end; $i++) :
                                            if ($i == $halamanAktif) : ?>
                                                <li class="page-item active"><a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                                            <?php else : ?>
                                                <li class="page-item"><a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                                        <?php endif;
                                        endfor; ?>
                                        <li class="page-item"><a class="page-link" href="?page=<?= min($jumlahHalaman, $halamanAktif + 1); ?>">Next</a></li>
                                    </ul>
                                </div>
                            </div>
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
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- Sweetalert -->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.tombol-hapus').on('click', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Data Akan Dihapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: href,
                            type: 'GET',
                            success: function(response) {
                                let res = JSON.parse(response);
                                if (res.status === 'success') {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'Data Berhasil Dihapus',
                                        icon: 'success',
                                        showConfirmButton: true,
                                    }).then(() => {
                                        window.location.href = '../user_management';
                                    });
                                } else if (res.status === 'error') {
                                    Swal.fire('Error', 'Data Gagal Dihapus', 'error');
                                } else if (res.status === 'redirect') {
                                    window.location.href = '../login';
                                }
                            },
                            error: function() {
                                Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                            }
                        });
                    }
                });
            });

            // Fungsi untuk menangani kueri pencarian
            function handleSearchQuery() {
                var keyword = $('#keyword').val();
                $.get('../ajax/data_users.php?keyword=' + keyword, function(data) {
                    $('#tabel').html(data);
                    // Initialize ulang tombol-hapus setelah memuat data baru
                    $('.tombol-hapus').on('click', function(e) {
                        e.preventDefault();
                        const href = $(this).attr('href');

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Data Akan Dihapus",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: href,
                                    type: 'GET',
                                    success: function(response) {
                                        let res = JSON.parse(response);
                                        if (res.status === 'success') {
                                            Swal.fire({
                                                title: 'Deleted!',
                                                text: 'Data Berhasil Dihapus',
                                                icon: 'success',
                                                showConfirmButton: true
                                            }).then(() => {
                                                window.location.href = '../user_management';
                                            });
                                        } else if (res.status === 'error') {
                                            Swal.fire('Error', 'Data Gagal Dihapus', 'error');
                                        } else if (res.status === 'redirect') {
                                            window.location.href = '../login';
                                        }
                                    },
                                    error: function() {
                                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                                    }
                                });
                            }
                        });
                    });
                });
            }

            // Sembunyikan tombol cari saat halaman dimuat
            $('#tombol-cari').hide();

            // Event ketika tombol cari ditekan
            $('#tombol-cari').on('click', function(e) {
                e.preventDefault();
                handleSearchQuery();
            });

            // Event ketika mengetik di kolom pencarian
            $('#keyword').on('keyup', function() {
                handleSearchQuery();
            });
        });
    </script>
</body>

</html>