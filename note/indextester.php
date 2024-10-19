<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

$jumlahDataPerHalaman = 10;
$keyword = isset($_POST["keyword"]) ? $_POST["keyword"] : '';

// Cek apakah ada pencarian
if (!empty($keyword)) {
    if ($role == 'Admin') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE nama_siswa LIKE '%$keyword%'"));
    } elseif ($role == 'Staff') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE user_id = $user_id AND nama_siswa LIKE '%$keyword%'"));
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

$startData = ($halamanAktif - 1) * $jumlahDataPerHalaman;

// Query berdasarkan pencarian dan role
if (!empty($keyword)) {
    if ($role == 'Admin') {
        $d_siswa = query("SELECT * FROM siswa WHERE nama_siswa LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
    } elseif ($role == 'Staff') {
        $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id AND nama_siswa LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Siswa</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php require_once '../partials/navbar.php' ?>
        <!-- Sidebar -->
        <?php require_once '../partials/sidebar.php' ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Siswa</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Data Siswa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <?php if ($role == 'Staff') : ?>
                                        <h3 class="card-title"><a href="add_siswa.php" class="btn btn-sm btn-block bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a></h3>
                                    <?php endif; ?>
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

                                <!-- Table -->
                                <div class="card-body table-responsive p-0" id="tabel">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Alamat</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Kelas</th>
                                                <th>Jenis Kelamin</th>
                                                <th>No Telepon</th>
                                                <th>Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($jumlahData == 0) {
                                                echo '<tr><td colspan="9" class="text-center">No data found</td></tr>';
                                                return; // Stop script jika tidak ada data
                                            }
                                            ?>
                                            <?php $n = 1 + $startData; ?>
                                            <?php foreach ($d_siswa as $siswa) : ?>
                                                <tr>
                                                    <td><?= $n++; ?></td>
                                                    <td><?= $siswa["nis"]; ?></td>
                                                    <td><?= $siswa["nama_siswa"]; ?></td>
                                                    <td><?= $siswa["alamat"]; ?></td>
                                                    <td><?= $siswa["tanggal_lahir"]; ?></td>
                                                    <td><?= $siswa["kelas"]; ?></td>
                                                    <td><?= $siswa["jenis_kelamin"]; ?></td>
                                                    <td><?= $siswa["no_telfon"]; ?></td>
                                                    <td><?= $siswa["email"]; ?></td>
                                                    <td>
                                                        <a href="edit_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>">Edit</a>
                                                        <a href="delete_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>" class="tombol-hapus">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="card-footer clearfix">
                                    <div class="showing-entries">
                                        <span id="showing-entries">Showing <?= ($startData + 1); ?> to <?= min($startData + $jumlahDataPerHalaman, $jumlahData); ?> of <?= $jumlahData; ?> entries</span>
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <li class="page-item"><a class="page-link" href="?page=<?= max(1, $halamanAktif - 1); ?>">Previous</a></li>
                                            <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>
                                                <li class="page-item <?= $i == $halamanAktif ? 'active' : ''; ?>"><a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a></li>
                                            <?php endfor; ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?= min($jumlahHalaman, $halamanAktif + 1); ?>">Next</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php require_once '../partials/footer.php' ?>
    </div>

    <!-- Scripts -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                                        window.location.href = '../data_siswa';
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
                $.get('../ajax/data_siswa.php?keyword=' + keyword, function(data) {
                    $('#tabel').html(data);

                    // Cek apakah ada data yang ditemukan
                    if (data.trim() === "") {
                        $('#tabel').html('<tr><td colspan="9" class="text-center">No data found</td></tr>');
                        updateShowingEntries(0, 0, 1);
                    } else {
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
                                                    window.location.href = '../data_siswa';
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
                    }
                });
            }

            // Fungsi untuk memperbarui tampilan showing entries
            function updateShowingEntries(jumlahData, jumlahDataPerHalaman, halamanSekarang) {
                if (jumlahData === 0) {
                    $('#showing-entries').html('Showing 0 entries');
                } else {
                    var startEntry = (halamanSekarang - 1) * jumlahDataPerHalaman + 1;
                    var endEntry = Math.min(halamanSekarang * jumlahDataPerHalaman, jumlahData);
                    $('#showing-entries').html('Showing ' + startEntry + ' to ' + endEntry + ' of ' + jumlahData + ' entries');
                }
            }

            // Event ketika tombol cari ditekan
            $('#tombol-cari').on('click', function(e) {
                e.preventDefault();
                handleSearchQuery(); // Panggil fungsi untuk menangani kueri pencarian
            });

            // Ubah event keyup menjadi click pada tombol pencarian
            $('#keyword').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Mencegah perilaku default tombol Enter
                    $('#tombol-cari').click(); // Simulasikan klik pada tombol pencari
                }
            });

        });
    </script>

</body>

</html>