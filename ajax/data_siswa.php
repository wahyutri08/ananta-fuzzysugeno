<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$keyword = mysqli_real_escape_string($db, $keyword);

$query = "SELECT * FROM siswa WHERE
                nis LIKE '%$keyword%' OR
                nama_siswa LIKE '%$keyword%' OR
                kelas LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                no_telfon LIKE '%$keyword%'
                LIMIT $awalData, $jumlahDataPerHalaman";

$d_siswa = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM siswa WHERE 
               nis LIKE '%$keyword%' OR
                nama_siswa LIKE '%$keyword%' OR
                kelas LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                no_telfon LIKE '%$keyword%'";
$resultTotal = query($queryTotal);
$jumlahData = $resultTotal[0]['jumlah'];

// Menghitung jumlah halaman
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Mendefinisikan tautan pagination secara langsung
$pagination = '<div class="card-footer clearfix">';
$pagination = '<ul class="pagination pagination-sm m-0 float-right">';
$pagination .= '<li class="page-item"><a class="page-link" href="?page=' . max(1, $page - 1) . '">Previous</a></li>';

$jumlahTampil = min(5, $jumlahHalaman);
$start = max(1, min($page - floor($jumlahTampil / 2), $jumlahHalaman - $jumlahTampil + 1));
$end = min($start + $jumlahTampil - 1, $jumlahHalaman);

for ($i = $start; $i <= $end; $i++) {
    if ($i == $page) {
        $pagination .= '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    } else {
        $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}
$pagination .= '<li class="page-item"><a class="page-link" href="?page=' . min($jumlahHalaman, $page + 1) . '">Next</a></li>';
$pagination .= '</ul>';
$pagination = '</div>';
?>

<div class="card-body table-responsive p-0" id="tabel">
    <table class="table table-hover text-nowrap">
        <thead>
            <tr>
                <th style="width: 10px">No</th>
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
            <?php $n = 1; ?>
            <?php foreach ($d_siswa as $siswa) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <td><?= $siswa["nis"]; ?></td>
                    <td><?= $siswa["nama_siswa"]; ?></td>
                    <td><?= $siswa["alamat"]; ?></td>
                    <td><?= $siswa["tanggal_lahir"]; ?></td>
                    <td><?= $siswa["kelas"]; ?></td>
                    <td><?= $siswa["jenis_kelamin"]; ?></td>
                    <td><?= $siswa["no_telfon"]; ?></td>
                    <td><?= $siswa["email"]; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="edit_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>">Edit</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="delete_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php $n++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>