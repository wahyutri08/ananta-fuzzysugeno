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
if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) {
    $halamanAktif = (int)$_GET["page"];
} else {
    $halamanAktif = 1;
}


$rules_fuzzy = query("SELECT * FROM rule_fuzzy LIMIT " . (($halamanAktif - 1) * $jumlahDataPerHalaman) . ", $jumlahDataPerHalaman");

$variabel = query("SELECT * FROM variabel");

// if (isset($_POST["search"])) {
//     $rules_fuzzy = searchRules($_POST["keyword"]);
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
                            <h1 class="m-0">Rule Fuzzy</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Rule Fuzzy</li>
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
                                    <table class="table table-hover text-nowrap" id="example2">
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
                                            <?php $n = ($halamanAktif - 1) * $jumlahDataPerHalaman + 1; ?>
                                            <?php foreach ($rules_fuzzy as $f) : ?>
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
                                <div class="card-footer clearfix">
                                    <div class="showing-entries">
                                        <span id="showing-entries">Showing 1 to 10 of <?= $jumlahData ?> entries</span>
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item"><a class="page-link" href="?page=<?= max(1, $halamanAktif - 1); ?>">Previous</a></li>
                                            <?php
                                            $jumlahTampil = min(5, $jumlahHalaman);
                                            $start = max(1, min($halamanAktif - floor($jumlahTampil / 2), $jumlahHalaman - $jumlahTampil + 1));
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
    <!-- Custom Pagination Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil total data dan data per halaman dari PHP
            const totalEntries = <?= $jumlahData ?>;
            const entriesPerPage = <?= $jumlahDataPerHalaman ?>;
            const currentPage = <?= $halamanAktif ?>;

            // Hitung entri awal dan entri akhir
            const startEntry = (currentPage - 1) * entriesPerPage + 1;
            const endEntry = Math.min(startEntry + entriesPerPage - 1, totalEntries);

            // Update teks di elemen showing-entries
            const showingEntries = document.getElementById('showing-entries');
            showingEntries.textContent = `Showing ${startEntry} to ${endEntry} of ${totalEntries} entries`;
        });
    </script>
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
                                        window.location.href = '../rule_fuzzy';
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
                $.get('../ajax/rule_fuzzy.php?keyword=' + keyword, function(data) {
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
                                                window.location.href = '../rule_fuzzy';
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