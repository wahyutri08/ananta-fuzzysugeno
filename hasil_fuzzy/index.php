<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}
date_default_timezone_set('Asia/Jakarta');

$jumlahDataPerHalaman = 10;

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];
$date_report = $_GET['date_report'] ?? '';
$filter_user_id = $_GET['user_id'] ?? '';
$nis_siswa = $_GET['nis'] ?? '';
$keterangan = $_GET['keterangan'] ?? '';

// Query dasar menghitung jumlah data
$queryCount = "SELECT COUNT(*) AS total 
               FROM hasil_fuzzy hf
               JOIN users u ON hf.user_id = u.id
               WHERE 1=1";

// Filter berdasarkan tanggal
if (!empty($date_report)) {
    $queryCount .= " AND DATE(hf.date_report) = '$date_report'";
}

// Filter berdasarkan user_id untuk Staff
if ($role == 'Staff') {
    $queryCount .= " AND hf.user_id = '$user_id'";
} elseif (!empty($filter_user_id) && $filter_user_id !== 'all') {
    $queryCount .= " AND hf.user_id = '$filter_user_id'";
}

// Filter berdasarkan NIS siswa
if (!empty($nis_siswa) && $nis_siswa !== 'all') {
    $queryCount .= " AND hf.nis = '$nis_siswa'";
}

// Filter berdasarkan status keterangan
if (!empty($keterangan) && $keterangan !== 'all') {
    $queryCount .= " AND hf.keterangan = '$keterangan'";
}

// Hitung jumlah data setelah filter
$jumlahData = query($queryCount)[0]['total'];
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
$halamanAktif = (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) ? (int)$_GET["page"] : 1;
$startData = ($halamanAktif - 1) * $jumlahDataPerHalaman;

// Query untuk mengambil data dengan filter dan paginasi
$queryData = "SELECT hf.*, u.nama AS nama_user 
              FROM hasil_fuzzy hf
              JOIN users u ON hf.user_id = u.id
              WHERE 1=1";

// Filter berdasarkan tanggal
if (!empty($date_report)) {
    $queryData .= " AND DATE(hf.date_report) = '$date_report'";
}

// Filter berdasarkan user_id untuk Staff
if ($role == 'Staff') {
    $queryData .= " AND hf.user_id = '$user_id'";
} elseif (!empty($filter_user_id) && $filter_user_id !== 'all') {
    $queryData .= " AND hf.user_id = '$filter_user_id'";
}

// Filter berdasarkan NIS siswa
if (!empty($nis_siswa) && $nis_siswa !== 'all') {
    $queryData .= " AND hf.nis = '$nis_siswa'";
}

// Filter berdasarkan status keterangan
if (!empty($keterangan) && $keterangan !== 'all') {
    $queryData .= " AND hf.keterangan = '$keterangan'";
}

// Batasi data sesuai dengan paginasi
$queryData .= " LIMIT $startData, $jumlahDataPerHalaman";

$result = query($queryData);
$users = query("SELECT * FROM users");
$siswa = query("SELECT * FROM siswa");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Hasil</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../assets/plugins/select2/css/select2.min.css">
    <!-- <link href="../assets/plugins/fontawesome-free/css/fontawesome.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/brands.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/solid.css" rel="stylesheet" /> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once '../partials/navbar.php' ?>
        <!-- Main Sidebar Container -->
        <?php require_once '../partials/sidebar.php' ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Laporan Hasil</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Laporan</li>
                                <li class="breadcrumb-item active">Laporan Hasil Analisa</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Filter Search</h3>
                                </div>
                                <form method="GET" action="">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="tanggal_lahir">Tanggal Laporan Hasil:</label>
                                                <input type="date" class="form-control" name="date_report" id="date_report" value="<?= htmlspecialchars($date_report); ?>">
                                            </div>
                                            <?php if ($role == 'Admin'): ?>
                                                <div class="form-group col-md-3">
                                                    <label>Staff:</label>
                                                    <select class="select2" name="user_id" style="width: 100%;">
                                                        <option value="all">-All Staff-</option>
                                                        <?php foreach ($users as $user) : ?>
                                                            <option value="<?= $user['id']; ?>" <?= $filter_user_id == $user['id'] ? 'selected' : ''; ?>><?= $user['nama']; ?> (<?= $user['username']; ?>)</option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                            <div class="form-group col-md-3">
                                                <label>NIS Siswa:</label>
                                                <select class="select2" name="nis" style="width: 100%;">
                                                    <option value="all">-All Siswa-</option>
                                                    <?php foreach ($siswa as $s) : ?>
                                                        <option value="<?= $s['nis']; ?>" <?= $nis_siswa == $s['nis'] ? 'selected' : ''; ?>>(<?= $s['nis']; ?>) <?= $s['nama_siswa']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="keterangan">Status:</label>
                                                <select class="form-control" name="keterangan" id="keterangan">
                                                    <option value="all" selected>-All Status-</option>
                                                    <option value="Layak" <?= $keterangan == 'Layak' ? 'selected' : ''; ?>>Layak</option>
                                                    <option value="Tidak Layak" <?= $keterangan == 'Tidak Layak' ? 'selected' : ''; ?>>Tidak Layak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <button type="button" class="btn btn-sm btn-primary ml-1" onclick="window.location.href='cetak.php?date_report=<?= $date_report; ?>&user_id=<?= $filter_user_id; ?>&nis=<?= $nis_siswa; ?>&keterangan=<?= $keterangan; ?>'">
                                                    <i class="fa fa-file-pdf"></i> Cetak
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card card-outline card-warning">
                                <div class="card-body table-responsive p-0" id="tabel">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <?php if ($role == 'Admin') {
                                                    echo '<th>Nama Staff</th>';
                                                } else {
                                                    echo '';
                                                } ?>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                                <th>Status Beasiswa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result): ?>
                                                <?php foreach ($result as $index => $row): ?>
                                                    <tr>
                                                        <td><?= $index + 1 + $startData; ?></td>
                                                        <?php if ($role == 'Admin') {
                                                            echo '<td>' . htmlspecialchars($row["nama_user"]) . '</td>';
                                                        } else {
                                                            echo '';
                                                        } ?>
                                                        <td><?= htmlspecialchars($row['nis']); ?></td>
                                                        <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                                                        <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                    <li><a class="dropdown-item" href="cetak_hasil.php?id_hasil=<?= $row["id_hasil"]; ?>" target="_blank">Cetak</a></li>
                                                                    <?php
                                                                    if ($role == 'Admin') {
                                                                        echo '<li><a class="dropdown-item tombol-hapus" href="delete_hasil.php?id_hasil=' . $row['id_hasil'] . '">Delete</a></li>';
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td class="text-center" colspan="6">No data found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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
                </div>
            </div>
        </div>
        <?php require_once '../partials/footer.php' ?>
    </div>

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="../assets/plugins/select2/js/select2.full.min.js"></script>
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
                                        window.location.href = '../hasil_fuzzy';
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

            function updateShowingEntries(jumlahData, jumlahDataPerHalaman, halamanSekarang) {
                if (jumlahData === 0) {
                    $('#showing-entries').html('Showing 0 entries');
                } else {
                    var startEntry = (halamanSekarang - 1) * jumlahDataPerHalaman + 1;
                    var endEntry = Math.min(halamanSekarang * jumlahDataPerHalaman, jumlahData);
                    $('#showing-entries').html('Showing ' + startEntry + ' to ' + endEntry + ' of ' + jumlahData + ' entries');
                }
            }

            $('#tombol-cari').on('click', function(e) {
                e.preventDefault();
            });
        });
    </script>
    <script>
        $(function() {
            $('.select2').select2()
        });
    </script>
</body>

</html>