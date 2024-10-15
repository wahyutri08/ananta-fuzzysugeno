<?php
require_once '../functions.php';
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

$jumlahDataPerHalaman = 10;
$awalData = ($jumlahDataPerHalaman * $page) - $jumlahDataPerHalaman;

$keyword = mysqli_real_escape_string($db, $keyword);

$query = "SELECT * FROM atribut WHERE 
            nama_atribut LIKE '%$keyword%' OR
            id_atribut LIKE '%$keyword%'
            LIMIT $awalData, $jumlahDataPerHalaman";

$atribut = query($query);

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM atribut WHERE nama_atribut LIKE '%$keyword%' OR id_atribut LIKE '%$keyword%'";
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
                <th>ID Atribut</th>
                <th>Nama Atribut</th>
                <th></th>
            </tr>
        </thead>
        <?php foreach ($atribut as $row) : ?>
            <tbody>
                <tr>
                    <td><?= $row["id_atribut"]; ?></td>
                    <td><?= $row["nama_atribut"]; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="edit_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>">Edit</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="delete_atribut.php?id_atribut=<?= $row["id_atribut"]; ?>">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        <?php endforeach; ?>
    </table>
</div>
<!-- Tampilkan pagination -->
<?= $pagination ?>