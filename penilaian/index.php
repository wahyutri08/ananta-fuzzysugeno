<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

$variabel = query("SELECT * FROM variabel");
$jumlahDataPerHalaman = 10;
if (isset($_POST["keyword"])) {
    $keyword = $_POST["keyword"];
} else {
    $keyword = '';
}

if (!empty($keyword)) {
    if ($role == 'Admin') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%'"));
    } elseif ($role == 'Staff') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE user_id = $user_id AND 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%'"));
    }
} else {
    if ($role == 'Admin') {
        $jumlahData = count(query("SELECT * FROM siswa"));
    } elseif ($role == 'Staff') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE user_id = $user_id"));
    }
}

$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) {
    $halamanAktif = (int)$_GET["page"];
} else {
    $halamanAktif = 1;
}
$startData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// Query berdasarkan role pengguna
if (!empty($keyword)) {
    if ($role == 'Admin') {
        $d_siswa = query("SELECT * FROM siswa WHERE 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
    } elseif ($role == 'Staff') {
        $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id AND 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
    }
} else {
    if ($role == 'Admin') {
        $d_siswa = query("SELECT * FROM siswa LIMIT $startData, $jumlahDataPerHalaman");
    } elseif ($role == 'Staff') {
        $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id LIMIT $startData, $jumlahDataPerHalaman");
    }
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
    <title>Penilaian</title>

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
                            <h1 class="m-0">Penilaian</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item active">Penilaian</li>
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
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <?php foreach ($variabel as $v) : ?>
                                                    <th><?= $v["nama_variabel"] ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($jumlahData > 0): ?>
                                                <?php foreach ($d_siswa as $siswa) : ?>
                                                    <tr>
                                                        <td><?= $siswa["nis"]; ?></td>
                                                        <td><?= $siswa["nama_siswa"]; ?></td>
                                                        <?php foreach ($variabel as $v) : ?>
                                                            <td>
                                                                <?php $penilaian = query("SELECT * FROM penilaian WHERE id_siswa = " . $siswa['id_siswa'] . " AND id_variabel = " . $v['id_variabel']);
                                                                if ($penilaian) {
                                                                    echo $penilaian[0]['nilai'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php endforeach; ?>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                    <li><a class="dropdown-item" href="editnilai.php?id_siswa=<?= $siswa["id_siswa"]; ?>" onclick="edit(<?= $siswa['id_siswa'] ?>)">Edit</a></li>
                                                                    <?php
                                                                    // Pastikan $siswa dan $siswa terdefinisi dan memiliki nilai sebelum digunakan
                                                                    if (isset($penilaian[0]['nilai']) && isset($siswa["id_siswa"])) {
                                                                        if ($penilaian[0]['nilai'] !== null && $penilaian[0]['nilai'] !== "") {
                                                                            echo '<li><a class="dropdown-item tombol-hapus" href="delete_nilai.php?id_siswa=' . $siswa['id_siswa'] . '">Delete</a></li>';
                                                                        } else {
                                                                            echo "";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">No data found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <div class="showing-entries">
                                        <span id="showing-entries">Showing <?= ($startData + 1); ?> to <?= min($startData + $jumlahDataPerHalaman, $jumlahData); ?> of <?= $jumlahData; ?> entries</span>
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <!-- Tombol Previous -->
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= max(1, $halamanAktif - 1); ?>">Previous</a>
                                            </li>

                                            <?php
                                            $startPage = max(1, $halamanAktif - 2);
                                            $endPage = min($jumlahHalaman, $halamanAktif + 2);

                                            if ($halamanAktif <= 3) {
                                                $endPage = min($jumlahHalaman, 5);
                                            }
                                            if ($halamanAktif > $jumlahHalaman - 3) {
                                                $startPage = max(1, $jumlahHalaman - 4);
                                            }

                                            for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                                <li class="page-item <?= $i == $halamanAktif ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= min($jumlahHalaman, $halamanAktif + 1); ?>">Next</a>
                                            </li>
                                        </ul>
                                    </div>
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
                                        window.location.href = '../penilaian';
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
            $('#keyword').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#tombol-cari').click();
                }
            });
        });
    </script>
</body>

</html>