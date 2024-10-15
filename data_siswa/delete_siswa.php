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

$id_siswa = $_GET["id_siswa"];

if (deleteSiswa($id_siswa) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
