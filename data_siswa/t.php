<div class="col-md-6">
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" enctype="multipart/form-data" id="myForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="username">NIS <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="nis" id="nis" placeholder="NIS" required>
                </div>
                <div class="form-group">
                    <label for="nama_siswa">Nama Siswa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" placeholder="Nama Siswa" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" required>
                </div>
            </div>




            <?php
            session_start();
            include_once("../auth_check.php");
            if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
                header("Location: ../login");
                exit;
            }

            $jumlahDataPerHalaman = 10;
            $jumlahData = count(query("SELECT * FROM siswa"));
            $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
            $halamanAktif = (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) ? (int)$_GET["page"] : 1;
            $awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

            $d_siswa = query("SELECT * FROM siswa LIMIT $awalData, $jumlahDataPerHalaman");

            if (isset($_POST["search"])) {
                $d_siswa = searchSiswa($_POST["keyword"]);
            }

            ?>



            <form method="POST" action="" enctype="multipart/form-data" id="myForm">
                <input type="hidden" name="id_siswa" value="<?= $siswa["id_siswa"]; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body">
                            <?php foreach ($variabel as $v) : ?>
                                <div class="form-group row">
                                    <label for="<?= $v["id_variabel"]; ?>" class="col-sm-5 control-label"><?= $v["nama_variabel"]; ?> <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <?php $penilaian = query("SELECT * FROM penilaian WHERE id_siswa = " . $siswa['id_siswa'] . " AND id_variabel = " . $v['id_variabel']); ?>
                                        <div class="input-group">
                                            <?php if ($penilaian) {
                                                echo '<input type="number" class="form-control" name="' . $v['id_variabel'] . '" id="' . $v['id_variabel'] . '" placeholder="Nilai" value="' . $penilaian[0]['nilai'] . '">';
                                            } else {
                                                echo '<input type="number" class="form-control" name="' . $v['id_variabel'] . '" id="' . $v['id_variabel'] . '" placeholder="Nilai" value="">';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-success">Submit Change</button>
                </div>
            </form>