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
if (isset($_POST["keyword"])) {
    $keyword = $_POST["keyword"];
} else {
    $keyword = '';
}

if (!empty($keyword)) {
    if ($role == 'Admin') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE 
                    (nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' OR
                    kelas LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    no_telfon LIKE '%$keyword%')"));
    } elseif ($role == 'Staff') {
        $jumlahData = count(query("SELECT * FROM siswa WHERE user_id = $user_id AND 
                    (nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' OR
                    kelas LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    no_telfon LIKE '%$keyword%')"));
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
        $d_siswa = query("SELECT * FROM siswa WHERE 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' OR
                    kelas LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    no_telfon LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
    } elseif ($role == 'Staff') {
        $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id AND 
                    nis LIKE '%$keyword%' OR
                    nama_siswa LIKE '%$keyword%' OR
                    kelas LIKE '%$keyword%' OR
                    email LIKE '%$keyword%' OR
                    no_telfon LIKE '%$keyword%' LIMIT $startData, $jumlahDataPerHalaman");
    }
} else {
    if ($role == 'Admin') {
        $d_siswa = query("SELECT * FROM siswa LIMIT $startData, $jumlahDataPerHalaman");
    } elseif ($role == 'Staff') {
        $d_siswa = query("SELECT * FROM siswa WHERE user_id = $user_id LIMIT $startData, $jumlahDataPerHalaman");
    }
}
