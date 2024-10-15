<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$keyword = mysqli_real_escape_string($db, $keyword);

$query = "SELECT * FROM cluster WHERE 
            nama_cluster LIKE '%$keyword%' OR
            id_cluster LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataPerHalaman";

$cluster = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM cluster WHERE nama_cluster LIKE '%$keyword%' OR
            id_cluster LIKE '%$keyword%'";
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

<table class="table color-table info-table">
    <thead>
        <tr>
            <th>ID Cluster</th>
            <th>Nama Cluster</th>
            <th></th>
        </tr>
    </thead>
    <?php foreach ($cluster as $row) : ?>
        <tbody>
            <tr>
                <td><?= $row["id_cluster"]; ?></td>
                <td><?= $row["nama_cluster"]; ?></td>
                <td>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="edit_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>">Edit</a></li>
                            <li><a class="dropdown-item tombol-hapus" href="delete_cluster.php?id_cluster=<?= $row["id_cluster"]; ?>">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    <?php endforeach; ?>
</table>
<!-- Tampilkan pagination -->
<?= $pagination ?>