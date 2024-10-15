<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$keyword = mysqli_real_escape_string($db, $keyword);

$query = "SELECT * FROM kelurahan WHERE 
            nama_kelurahan LIKE '%$keyword%' OR
            id_kelurahan LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataPerHalaman";

$kelurahan = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM kelurahan WHERE nama_kelurahan LIKE '%$keyword%' OR id_kelurahan LIKE '%$keyword%'";
$resultTotal = query($queryTotal);
$jumlahData = $resultTotal[0]['jumlah'];

// Menghitung jumlah halaman
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// Mendefinisikan tautan pagination secara langsung
$pagination = '<ul class="pagination justify-content-end">';
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
?>

<div class="table-responsive">
    <table class="table color-table info-table">
        <thead>
            <tr>
                <th>ID Kelurahan</th>
                <th>Nama Kelurahan</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelurahan as $row) : ?>
                <tr>
                    <td><?= $row["id_kelurahan"]; ?></td>
                    <td><?= $row["nama_kelurahan"]; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="edit_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>">Edit</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="delete_kelurahan.php?id_kelurahan=<?= $row["id_kelurahan"]; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tampilkan pagination -->
<?= $pagination ?>