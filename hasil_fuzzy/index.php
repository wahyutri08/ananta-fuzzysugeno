<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

// Ambil hasil fuzzy dari database
$hasil_fuzzy = query("SELECT * FROM hasil_fuzzy");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Fuzzy</title>
</head>

<body>
    <h1>Hasil Perhitungan Fuzzy Beasiswa</h1>
    <table border="1">
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Nilai UTS</th>
                <th>Nilai UAS</th>
                <th>Keaktifan</th>
                <th>Penghasilan</th>
                <th>Nilai Fuzzy</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($hasil_fuzzy as $hasil) : ?>
                <tr>
                    <td><?= $hasil['nis'] ?></td>
                    <td><?= $hasil['nama_siswa'] ?></td>
                    <td><?= $hasil['nilai_uts'] ?></td>
                    <td><?= $hasil['nilai_uas'] ?></td>
                    <td><?= $hasil['keaktifan'] ?></td>
                    <td><?= $hasil['penghasilan'] ?></td>
                    <td><?= $hasil['nilai_fuzzy'] ?></td>
                    <td><?= $hasil['keterangan'] ?></td>
                    <td><?= $hasil['date_report'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>