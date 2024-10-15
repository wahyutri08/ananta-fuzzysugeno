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
$atribut = query("SELECT * FROM atribut");

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
                <th>ID</th>
                <th>Nama Kelurahan</th>
                <?php foreach ($atribut as $row) : ?>
                    <th><?= $row["nama_atribut"]; ?></th>
                <?php endforeach; ?>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kelurahan as $rows) : ?>
                <tr>
                    <td><?= $rows["id_kelurahan"]; ?></td>
                    <td><?= $rows["nama_kelurahan"]; ?></td>
                    <?php foreach ($atribut as $row) : ?>
                        <td>
                            <?php
                            $nilaikelurahan = query("SELECT * FROM nilai_kelurahan WHERE id_kelurahan = " . $rows['id_kelurahan'] . " AND id_atribut = " . $row['id_atribut']);
                            if ($nilaikelurahan) {
                                echo $nilaikelurahan[0]['nilai'];
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
                                <li><a class="dropdown-item" href="editnilai.php?id_kelurahan=<?= $rows["id_kelurahan"]; ?>" onclick="edit(<?= $rows['id_kelurahan'] ?>)">Edit</a></li>
                                <?php
                                // Pastikan $nilaikelurahan dan $rows terdefinisi dan memiliki nilai sebelum digunakan
                                if (isset($nilaikelurahan[0]['nilai']) && isset($rows["id_kelurahan"])) {
                                    if ($nilaikelurahan[0]['nilai'] !== null && $nilaikelurahan[0]['nilai'] !== "") {
                                        echo '<li><a class="dropdown-item tombol-hapus" href="deletenilai.php?id_kelurahan=' . $rows["id_kelurahan"] . '">Delete</a></li>';
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