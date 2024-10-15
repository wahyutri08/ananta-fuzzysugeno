<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$keyword = mysqli_real_escape_string($db, $keyword);

$query = "SELECT * FROM users WHERE
                id LIKE '%$keyword%' OR
                username LIKE '%$keyword%' OR
                nama LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                role LIKE '%$keyword%'
                LIMIT $awalData, $jumlahDataPerHalaman";

$users = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM users WHERE 
               id LIKE '%$keyword%' OR
                username LIKE '%$keyword%' OR
                nama LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                role LIKE '%$keyword%'";
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
                <th>Username</th>
                <th>Email</th>
                <th>Nama</th>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $n = 1; ?>
            <?php foreach ($users as $userData) : ?>
                <tr>
                    <td><?= $n; ?></td>
                    <!-- <td><?= $userData["id"]; ?></td> -->
                    <td><?= $userData["username"]; ?></td>
                    <td><?= $userData["nama"]; ?></td>
                    <td><?= $userData["email"]; ?></td>
                    <td>
                        <?php
                        if ($userData['role'] == 'Admin') {
                            echo '<div class="label label-table label-success">' . $userData["role"] . '</div>';
                        } else {
                            echo '<div class="label label-table label-info">' . $userData["role"] . '</div>';
                        }
                        ?>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="edit_users.php?id=<?= $userData["id"]; ?>">Edit</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="delete_users.php?id=<?= $userData["id"]; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php $n++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Tampilkan pagination -->
<!-- <?= $pagination ?> -->