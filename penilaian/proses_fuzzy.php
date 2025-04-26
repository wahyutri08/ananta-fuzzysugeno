<?php
session_start();
include_once("../auth_check.php");

// Pastikan user sudah login
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

// Ambil variabel yang digunakan untuk penilaian
$variabel = query("SELECT * FROM variabel");

// Ambil data siswa berdasarkan role
if ($role == 'Admin') {
    $d_siswa = query("SELECT * FROM siswa");
} elseif ($role == 'Staff') {
    $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id");
}

$data = []; // Array untuk menyimpan hasil proses fuzzy per siswa
$errors = []; // Array untuk menyimpan error jika ada kegagalan

// Loop melalui data siswa
foreach ($d_siswa as $siswa) {
    $id_siswa = $siswa['id_siswa'];
    $nis = $siswa['nis'];
    $nama_siswa = $siswa['nama_siswa'];

    $row = []; // Menyimpan nilai dari masing-masing variabel untuk siswa

    foreach ($variabel as $v) {
        $id_variabel = mysqli_real_escape_string($db, $v['id_variabel']);
        $id_siswa_safe = mysqli_real_escape_string($db, $siswa['id_siswa']);

        // Ambil data penilaian siswa berdasarkan variabel
        $nilai = query("SELECT nilai FROM penilaian WHERE id_siswa = $id_siswa_safe AND id_variabel = $id_variabel");

        // Cek apakah nilai ada, jika tidak berikan default 0
        if (!empty($nilai)) {
            $row[] = $nilai[0]['nilai'];
        } else {
            $row[] = 0;
        }
    }

    // Assign nilai variabel ke masing-masing variabel yang spesifik
    $nilai_uts = $row[0] ?? 0; // Nilai UTS
    $nilai_uas = $row[1] ?? 0; // Nilai UAS
    $keaktifan = $row[2] ?? 0; // Nilai keaktifan
    $penghasilan = $row[3] ?? 0; // Nilai penghasilan

    // Hitung fuzzy berdasarkan nilai-nilai
    $hasil_fuzzy = hitungFuzzy($nilai_uts, $nilai_uas, $keaktifan, $penghasilan);
    $nilai_fuzzy = $hasil_fuzzy['nilai'];
    $keterangan = $hasil_fuzzy['keterangan'];

    // Simpan hasil fuzzy ke database
    date_default_timezone_set('Asia/Jakarta');
    $simpan = simpanHasilFuzzy($user_id, $id_siswa, $nis, $nama_siswa, $nilai_uts, $nilai_uas, $keaktifan, $penghasilan, $nilai_fuzzy, $keterangan, date('Y-m-d'));

    // Cek apakah penyimpanan berhasil
    if (!$simpan) {
        $errors[] = "Gagal menyimpan hasil untuk siswa dengan NIS: $nis";
    }
}


if (empty($errors)) {
    echo json_encode(["status" => "success", "message" => "Semua data berhasil diproses dan disimpan"]);
} else {
    echo json_encode(["status" => "error", "message" => implode("; ", $errors)]);
}
exit;
