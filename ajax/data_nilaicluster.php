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
$atribut = query("SELECT * FROM atribut");

// Query untuk menghitung jumlah data total
$queryTotal = "SELECT COUNT(*) AS jumlah FROM cluster WHERE nama_cluster LIKE '%$keyword%' OR id_cluster LIKE '%$keyword%'";
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
                <th>Nama Cluster</th>
                <?php foreach ($atribut as $row) : ?>
                    <th><?= $row["nama_atribut"]; ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cluster as $rows) : ?>
                <tr>
                    <td><?= $rows["nama_cluster"]; ?></td>
                    <?php foreach ($atribut as $row) : ?>
                        <td>
                            <?php
                            $nilaicluster = query("SELECT * FROM nilai_cluster WHERE id_cluster = " . $rows['id_cluster'] . " AND id_atribut = " . $row['id_atribut']);
                            if ($nilaicluster) {
                                echo $nilaicluster[0]['nilai'];
                            } else {
                                echo " ";
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <td>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="editnilai.php?id_cluster=<?= $rows["id_cluster"]; ?>" onclick="edit(<?= $rows['id_cluster'] ?>)">Edit</a></li>
                                <?php
                                // Pastikan $nilaikelurahan dan $rows terdefinisi dan memiliki nilai sebelum digunakan
                                if (isset($nilaicluster[0]['nilai']) && isset($rows["id_cluster"])) {
                                    if ($nilaicluster[0]['nilai'] !== null && $nilaicluster[0]['nilai'] !== "") {
                                        echo '<li><a class="dropdown-item tombol-hapus" href="deletenilai.php?id_cluster=' . $rows["id_cluster"] . '">Delete</a></li>';
                                    } else {
                                        echo "";
                                    }
                                }
                                ?>
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