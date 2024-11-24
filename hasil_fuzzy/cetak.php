<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

// Ambil filter dari URL atau form
$date_report = $_GET['date_report'] ?? '';
$filter_user_id = $_GET['user_id'] ?? '';
$nis_siswa = $_GET['nis'] ?? '';
$keterangan = $_GET['keterangan'] ?? '';
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

$variabel = query("SELECT * FROM variabel");

// Query untuk mengambil data hasil dengan filter yang diterapkan
$query = "SELECT hf.*, u.nama AS nama_user 
          FROM hasil_fuzzy hf
          JOIN users u ON hf.user_id = u.id
          WHERE 1=1";

// Filter tanggal
if (!empty($date_report)) {
    $query .= " AND DATE(hf.date_report) = '$date_report'";
}

// Filter berdasarkan user_id untuk Staff
if ($role == 'Staff') {
    $query .= " AND hf.user_id = '$user_id'";
} elseif (!empty($filter_user_id) && $filter_user_id !== 'all') {
    $query .= " AND hf.user_id = '$filter_user_id'";
}

// Filter berdasarkan NIS siswa
if (!empty($nis_siswa) && $nis_siswa !== 'all') {
    $query .= " AND hf.nis = '$nis_siswa'";
}

// Filter berdasarkan status keterangan
if (!empty($keterangan) && $keterangan !== 'all') {
    $query .= " AND hf.keterangan = '$keterangan'";
}

// Eksekusi query
$result = query($query);

// Mulai buffer output untuk membuat tampilan PDF
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Beasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header img {
            width: 60px;
            height: 60px;
            vertical-align: middle;
        }

        .header .title {
            font-size: 16px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 14px;
        }

        .header .contact {
            font-size: 10px;
        }

        h1 {
            color: goldenrod;
        }

        .report {
            width: 100%;
            max-width: 600px;
            margin: auto;
            border-collapse: collapse;
        }

        .report th {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .report td {
            border: 1px solid #000;
            padding: 8px;
        }

        .report-header1 {
            margin-bottom: 25px;
        }

        .report-header td {
            padding: 5px;
        }

        .result {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td width="15%">
                    <img src="../assets/dist/img/logo2.png" alt="Logo Yayasan" width="100" height="100">
                </td>
                <td width="70%" align="center">
                    <div class="title">
                        <h1><strong>SMKS BINONG PERMAI</strong></h1>
                    </div>
                    <div class="contact">
                        PERUM BINONG PERMAI. CURUG. KAB. TANGERANG 15810 <br>
                        021-29872830
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="report-header1">
        <table width="100%">
            <tr>
                <td style="text-align: center;">
                    <h2><strong>Laporan Hasil Beasiswa</strong></h2>
                </td>
            </tr>
        </table>
    </div>
    <table class="report">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <?php foreach ($variabel as $v) : ?>
                    <th><?= $v["nama_variabel"]; ?></th>
                <?php endforeach; ?>
                <th>Status Beasiswa</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result): ?>
                <?php foreach ($result as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= htmlspecialchars($row['nis']); ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                        <td><?= htmlspecialchars($row['nilai_uts']); ?></td>
                        <td><?= htmlspecialchars($row['nilai_uas']); ?></td>
                        <td><?= htmlspecialchars($row['keaktifan']); ?></td>
                        <td><?= htmlspecialchars($row['penghasilan']); ?></td>
                        <td><?= htmlspecialchars($row['keterangan']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center" colspan="6">
                        No data found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>

<?php
$html = ob_get_clean();

// Inisialisasi mPDF dan buat PDF dari HTML
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Laporan_Hasil_Fuzzy.pdf', 'D');
