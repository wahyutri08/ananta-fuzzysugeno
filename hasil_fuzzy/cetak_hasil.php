<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

if (isset($_GET["id_hasil"])) {
    $id_hasil = $_GET["id_hasil"];
} else {
    header("Location: ../error/error.php?message=ID Siswa tidak ditemukan");
    exit;
}

$hasil_fuzzy = query("SELECT * FROM hasil_fuzzy WHERE id_hasil = $id_hasil")[0];

if ($id_hasil === null) {
    header("Location: ../error/error.php?message=ID Siswa tidak ditemukan");
    exit;
}

if (empty($hasil_fuzzy)) {
    header("Location: ../error/error.php?message=ID Siswa tidak valid");
    exit;
}
$variabel = query("SELECT * FROM variabel");

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $hasil_fuzzy["nama_siswa"]; ?></title>
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
            /* font-weight: bold; */
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

    <div class="report-header">
        <table width="100%">
            <tr>
                <td>Nama Siswa:</td>
                <td><?= $hasil_fuzzy["nama_siswa"]; ?></td>
            </tr>
        </table>
    </div>

    <table class="report">
        <thead>
            <tr>
                <th>Jenis Nilai</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nilai Ujian Tengah Semester</td>
                <td><?= $hasil_fuzzy['nilai_uts']; ?></td>
            </tr>
            <tr>
                <td>Nilai Ujian Akhir Semester</td>
                <td><?= $hasil_fuzzy['nilai_uas']; ?></td>
            </tr>
            <tr>
                <td>Nilai Keaktifan Sekolah</td>
                <td><?= $hasil_fuzzy['keaktifan']; ?></td>
            </tr>
            <tr>
                <td>Penghasilan Orang Tua</td>
                <td><?= $hasil_fuzzy['penghasilan']; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="result">
        Bahwa Hasil Dari Proses Perhitungan Penilaian Beasiswa <strong><?= $hasil_fuzzy["nama_siswa"]; ?></strong> : <strong><?= $hasil_fuzzy['keterangan']; ?></strong> Mendapatkan Beasiswa.
    </div>

</body>

</html>
<?php
$html = ob_get_clean();

// Inisialisasi mPDF dan buat PDF dari HTML
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Laporan_Hasil_Fuzzy.pdf', 'D');
