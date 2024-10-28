<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php'; // Pastikan path sesuai dengan lokasi autoload.php

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
$query = "SELECT hf.*, u.nama AS nama_user, s.nis, s.nama_siswa 
          FROM hasil_fuzzy hf
          JOIN users u ON hf.user_id = u.id
          JOIN siswa s ON hf.nis = s.nis
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
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">Laporan Hasil Beasiswa</h2>
    <table>
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
                    <td class="text-center" colspan="6">No data found</td>
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
$mpdf->Output('Laporan_Hasil_Fuzzy.pdf', 'I'); // Mengatur output PDF agar langsung ditampilkan di browser
