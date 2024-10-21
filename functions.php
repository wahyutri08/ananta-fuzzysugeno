<?php
$db = mysqli_connect("localhost", "root", "", "dev-ananta");

function query($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];

    // Periksa apakah query berhasil dieksekusi
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Loop melalui hasil query
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row; // Menambahkan baris hasil ke dalam array $rows
            }
        }
    } else {
        echo "Error: " . mysqli_error($db);
    }

    return $rows;
}

function register($data)
{
    global $db;

    $username = strtolower(stripcslashes($data["username"]));
    $email = strtolower(stripslashes($data["email"]));
    $nama = ucfirst(stripslashes($data["nama"]));
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);
    $role = htmlspecialchars($data["role"]);
    $status = htmlspecialchars($data["status"]);

    //  Upload Gambar
    $avatar = upload();
    if (!$avatar) {
        return -3;
    }

    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        // Jika Nama Username Sudah Ada
        return -1;
    }

    if ($password !== $password2) {
        // Password 1 tidak sesuai dengan password 2
        return -2;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($db, "INSERT INTO users VALUES('', '$username','$email', '$nama', '$password', '$role', '$status', '$avatar')");
    return mysqli_affected_rows($db);
}

function editUsers($data)
{
    global $db;
    $id = ($data["id"]);
    $username = strtolower(stripslashes($data["username"]));
    $nama = ucfirst(stripcslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $password = mysqli_real_escape_string($db, $data["password"]);
    $avatarLama = htmlspecialchars($data["avatarLama"]);
    $role = htmlspecialchars($data["role"]);
    $status = htmlspecialchars($data["status"]);
    // $usernameLama = htmlspecialchars($data["username"]);

    // Cek apakah user pilih avatar baru atau tidak
    if ($_FILES['avatar']['error'] === 4) {
        $avatar = $avatarLama;
    } else {
        $avatar = upload();
        if ($avatar === -1) {
            // Kesalahan Jika Bukan Gambar
            return -1;
        } elseif ($avatar === -2) {
            // Kesalahan Ukuran Terlalu Besar
            return -2;
        }
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
        username = '$username', 
        nama = '$nama', 
        email = '$email',
        password = '$password',
        role = '$role',
        status = '$status',
        avatar = '$avatar' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteUsers($id)
{
    global $db;
    mysqli_query($db, "DELETE FROM users WHERE id = $id");
    return mysqli_affected_rows($db);
}

function upload()
{

    $namaFile = $_FILES['avatar']['name'];
    $ukuranFiles = $_FILES['avatar']['size'];
    $error = $_FILES['avatar']['error'];
    $tmpName = $_FILES['avatar']['tmp_name'];

    // Cek apakah yang diupload adalah gambar
    $ekstensiAvatarValid = ['', 'jpg', 'jpeg', 'png'];
    $ekstensiAvatar = explode('.', $namaFile);
    $ekstensiAvatar = strtolower(end($ekstensiAvatar));
    if (!in_array($ekstensiAvatar, $ekstensiAvatarValid)) {
        // Jika Avatar Bukan Gambar
        return -1;
    }

    if ($ukuranFiles > 10000000) {
        // Cek jika ukuran terlalu besar
        return -2;
    }

    // Gambar Siap Upload
    // generate nama gambar baru

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiAvatar;

    move_uploaded_file($tmpName, '../assets/dist/img/' . $namaFileBaru);

    return $namaFileBaru;
}

function editProfile($data)
{
    global $db;
    $id = ($data["id"]);
    $nama = ucfirst(stripcslashes($data["nama"]));
    $email = strtolower(stripslashes($data["email"]));
    $avatarLama = htmlspecialchars($data["avatarLama"]);

    // Cek apakah user pilih avatar baru atau tidak
    if ($_FILES['avatar']['error'] === 4) {
        $avatar = $avatarLama;
    } else {
        $avatar = upload();
        if ($avatar === -1) {
            // Kesalahan Jika Bukan Gambar
            return -1;
        } elseif ($avatar === -2) {
            // Kesalahan Jika Ukuran Terlalu Besar
            return -2;
        }
    }

    $query = "UPDATE users SET 
        nama = '$nama',  
        email = '$email',
        avatar = '$avatar' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function changePassword($data)
{
    global $db;
    $id = ($data["id"]);
    $password = mysqli_real_escape_string($db, $data["password"]);
    $password2 = mysqli_real_escape_string($db, $data["password2"]);

    if ($password !== $password2) {
        return -1;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET 
    password = '$password' WHERE id = $id";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function addSiswa($data)
{
    global $db;

    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    $nis = htmlspecialchars($data["nis"]);
    $nama_siswa = htmlspecialchars($data["nama_siswa"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $tanggal_lahir = htmlspecialchars($data["tanggal_lahir"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
    $no_telfon = htmlspecialchars($data["no_telfon"]);
    $email = htmlspecialchars($data["email"]);

    // Periksa apakah NIS sudah ada di database
    $query = "SELECT * FROM siswa WHERE nis = '$nis'";
    $result = mysqli_query($db, $query);
    if (mysqli_fetch_assoc($result)) {
        // Jika NIS sudah ada, return -1
        return -1;
    }

    // Cek role pengguna
    if ($role == 'Staff') {
        $query = "INSERT INTO siswa 
                  VALUES ('','$user_id', '$nis', '$nama_siswa', '$alamat', '$tanggal_lahir', '$kelas', '$jenis_kelamin', '$no_telfon', '$email')";
    } elseif ($role == 'Admin') {
        $query = "INSERT INTO siswa 
                  VALUES ('', NULL, '$nis', '$nama_siswa', '$alamat', '$tanggal_lahir', '$kelas', '$jenis_kelamin', '$no_telfon', '$email')";
    }

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function editSiswa($data)
{
    global $db;
    $id_siswa = $data["id_siswa"];
    $nis = htmlspecialchars($data["nis"]);
    $nama_siswa = htmlspecialchars($data["nama_siswa"]);
    $alamat = htmlspecialchars($data["alamat"]);
    $tanggal_lahir = htmlspecialchars($data["tanggal_lahir"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $jenis_kelamin = htmlspecialchars($data["jenis_kelamin"]);
    $no_telfon = htmlspecialchars($data["no_telfon"]);
    $email = htmlspecialchars($data["email"]);
    $nis = mysqli_real_escape_string($db, $nis);

    // Periksa apakah nis siswa sudah ada, tetapi abaikan baris yang sedang diedit
    $query = "SELECT * FROM siswa WHERE nis = '$nis' AND id_siswa != $id_siswa";
    $result = mysqli_query($db, $query);

    if (mysqli_fetch_assoc($result)) {
        return -1;
    }

    // Jika nis siswa tidak ada yang duplikat, lakukan update
    $query = "UPDATE siswa SET nis = '$nis', 
                     nama_siswa = '$nama_siswa',
                     alamat = '$alamat',
                     tanggal_lahir = '$tanggal_lahir',
                     kelas = '$kelas',
                     jenis_kelamin = '$jenis_kelamin',
                     no_telfon = '$no_telfon',
                     email = '$email'
                      WHERE id_siswa = $id_siswa";
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function deleteSiswa($id_siswa)
{
    global $db;
    mysqli_query($db, "DELETE FROM siswa WHERE id_siswa = $id_siswa");
    return mysqli_affected_rows($db);
}

function dataPostNilai($postData, $getData, $role, $user_id)
{
    foreach ($postData as $key => $value) {
        if (
            $key == 'id_siswa' || $key == 'submit'
        ) {
            continue;
        }

        // Cek apakah data penilaian sudah ada
        $querySelect = query("SELECT * FROM penilaian WHERE id_variabel = " . $key . " AND id_siswa = " . $getData['id_siswa']);

        if (count($querySelect) > 0) {
            // Panggil fungsi edit sesuai dengan role
            editNilai($postData, $getData, $key, $role, $user_id);
        } else {
            // Panggil fungsi tambah sesuai dengan role
            tambahNilai($postData, $getData, $key, $role, $user_id);
        }
    }
}

function editNilai($post, $get, $key, $role, $user_id)
{
    global $db;

    // Cek role untuk menentukan query
    if ($role == 'Admin') {
        // Admin bisa mengedit semua data
        $query = "UPDATE penilaian SET 
                  nilai = '" . $post[$key] . "' 
                  WHERE id_variabel = '" . $key . "' 
                  AND id_siswa = '" . $get['id_siswa'] . "'";
    } elseif ($role == 'Staff') {
        // Staff hanya bisa mengedit data siswa yang terkait dengan mereka (user_id)
        $query = "UPDATE penilaian SET 
                  nilai = '" . $post[$key] . "' 
                  WHERE id_variabel = '" . $key . "' 
                  AND id_siswa = '" . $get['id_siswa'] . "' 
                  AND user_id = '" . $user_id . "'";
    }

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

function tambahNilai($post, $get, $key, $role, $user_id)
{
    global $db;

    // Cek role untuk menentukan query
    if ($role == 'Staff') {
        // Admin bisa menambah semua data
        $query = "INSERT INTO penilaian VALUES 
                  ('', 
                  " . $user_id . ", 
                  " . $get['id_siswa'] . ", 
                  " . $key . ", 
                  $post[$key])";
    } elseif ($role == 'Admin') {
        // Staff hanya bisa menambah data untuk siswa yang terkait dengan mereka (user_id)
        $query = "INSERT INTO penilaian VALUES 
                  ('', 
                  NULL,
                  " . $get['id_siswa'] . ", 
                  " . $key . ", 
                  $post[$key])";
    }

    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}


function deleteNilaiSiswa($id_siswa)
{
    global $db;
    mysqli_query($db, "DELETE FROM penilaian WHERE id_siswa = $id_siswa");
    return mysqli_affected_rows($db);
}

function addRules($data)
{
    global $db;
    $nilai_uts = mysqli_real_escape_string($db, $data['nilai_uts']);
    $nilai_uas = mysqli_real_escape_string($db, $data['nilai_uas']);
    $nilai_keaktifan = mysqli_real_escape_string($db, $data['nilai_keaktifan']);
    $nilai_penghasilan = mysqli_real_escape_string($db, $data['nilai_penghasilan']);
    $nilai = mysqli_real_escape_string($db, $data['nilai']);

    // Cek apakah data yang sama sudah ada di database
    $checkQuery = "SELECT COUNT(*) as count FROM rule_fuzzy 
                   WHERE nilai_uts = '$nilai_uts' 
                   AND nilai_uas = '$nilai_uas' 
                   AND nilai_keaktifan = '$nilai_keaktifan' 
                   AND nilai_penghasilan = '$nilai_penghasilan'";

    $result = mysqli_query($db, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    // Jika data sudah ada, return 0 atau pesan error
    if ($row['count'] > 0) {
        return 0;
    } else {
        $query = "INSERT INTO rule_fuzzy (nilai_uts, nilai_uas, nilai_keaktifan, nilai_penghasilan, nilai) 
                  VALUES ('$nilai_uts', 
                          '$nilai_uas', 
                          '$nilai_keaktifan', 
                          '$nilai_penghasilan', 
                          '$nilai')";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }
}

function editRule($data)
{
    global $db;
    $id_rule = $data["id_rule"];
    $nilai_uts = mysqli_real_escape_string($db, $data['nilai_uts']);
    $nilai_uas = mysqli_real_escape_string($db, $data['nilai_uas']);
    $nilai_keaktifan = mysqli_real_escape_string($db, $data['nilai_keaktifan']);
    $nilai_penghasilan = mysqli_real_escape_string($db, $data['nilai_penghasilan']);
    $nilai = mysqli_real_escape_string($db, $data['nilai']);

    $checkQuery = "SELECT COUNT(*) as count FROM rule_fuzzy 
                   WHERE nilai_uts = '$nilai_uts' 
                   AND nilai_uas = '$nilai_uas' 
                   AND nilai_keaktifan = '$nilai_keaktifan' 
                   AND nilai_penghasilan = '$nilai_penghasilan'";

    $result = mysqli_query($db, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    // Jika data sudah ada, return 0 atau pesan error
    if ($row['count'] > 0) {
        return 0;
    } else {
        $query = "UPDATE rule_fuzzy SET nilai_uts = '$nilai_uts',
                                        nilai_uas = '$nilai_uas',
                                        nilai_keaktifan = '$nilai_keaktifan',
                                        nilai_penghasilan = '$nilai_penghasilan',
                                        nilai = '$nilai' WHERE id_rule = $id_rule ";

        mysqli_query($db, $query);
        return mysqli_affected_rows($db);
    }
}

function deleteRule($id_rule)
{
    global $db;
    mysqli_query($db, "DELETE FROM rule_fuzzy WHERE id_rule = $id_rule");
    return mysqli_affected_rows($db);
}

function is_user_active($id)
{
    global $db;

    // Cek status pengguna berdasarkan ID
    $result = mysqli_query($db, "SELECT status FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);

    // Jika data ditemukan
    if ($row) {
        // Cek apakah statusnya 'Aktif'
        if ($row['status'] === 'Aktif') {
            return true;
        }
    }

    // Jika tidak aktif atau tidak ditemukan
    return false;
}


function logout()
{
    // Hapus semua data sesi
    $_SESSION = array();

    // Hapus cookie sesi jika ada
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Hancurkan sesi
    session_destroy();

    // Alihkan ke halaman login
    header("Location: ../login"); // Sesuaikan dengan halaman login Anda
    exit;
}

function searchUsers($keyword)
{
    $query = "SELECT * FROM users WHERE
                id LIKE '%$keyword%' OR
                username LIKE '%$keyword%' OR
                nama LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                role LIKE '%$keyword%'
             ";
    return query($query);
}

function searchSiswa($keyword)
{
    $query = "SELECT * FROM siswa WHERE
                nis LIKE '%$keyword%' OR
                nama_siswa LIKE '%$keyword%' OR
                kelas LIKE '%$keyword%' OR
                email LIKE '%$keyword%' OR
                no_telfon LIKE '%$keyword%'
             ";
    return query($query);
}

function generatePagination($jumlahHalaman, $halamanAktif)
{
    $pagination = '<ul class="pagination justify-content-end">';
    $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . max(1, $halamanAktif - 1) . '">Previous</a></li>';

    for ($i = 1; $i <= $jumlahHalaman; $i++) {
        if ($i == $halamanAktif) {
            $pagination .= '<li class="page-item active"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
    }

    $pagination .= '<li class="page-item"><a class="page-link" href="?page=' . min($jumlahHalaman, $halamanAktif + 1) . '">Next</a></li>';
    $pagination .= '</ul>';

    return $pagination;
}

// Fungsi untuk mengkategorikan nilai ke dalam kategori fuzzy (rendah, sedang, tinggi)
function categorize($tipe, $nilai)
{
    switch ($tipe) {
        case 'uts':
        case 'uas':
            if ($nilai > 80) return 'tinggi';   // Nilai tinggi
            if ($nilai > 60) return 'sedang';   // Nilai sedang
            return 'rendah';                     // Nilai rendah

        case 'keaktifan':
            if ($nilai > 70) return 'tinggi';   // Keaktifan tinggi
            if ($nilai > 50) return 'sedang';   // Keaktifan sedang
            return 'rendah';                     // Keaktifan rendah

        case 'penghasilan':
            if ($nilai < 2000000) return 'rendah';  // Penghasilan rendah
            if ($nilai < 5000000) return 'sedang';  // Penghasilan sedang
            return 'tinggi';                         // Penghasilan tinggi

        default:
            return 'tidak diketahui';
    }
}


// Fungsi untuk menghitung hasil fuzzy dari input nilai
function hitungFuzzy($nilai_uts, $nilai_uas, $keaktifan, $penghasilan)
{
    // Kategorikan nilai berdasarkan input
    $kategori_uts = categorize('uts', $nilai_uts);
    $kategori_uas = categorize('uas', $nilai_uas);
    $kategori_keaktifan = categorize('keaktifan', $keaktifan);
    $kategori_penghasilan = categorize('penghasilan', $penghasilan);

    // Query untuk mengambil aturan fuzzy berdasarkan kategori yang telah ditentukan
    $query = "SELECT * FROM rule_fuzzy
              WHERE nilai_uts = '$kategori_uts'
              AND nilai_uas = '$kategori_uas'
              AND nilai_keaktifan = '$kategori_keaktifan'
              AND nilai_penghasilan = '$kategori_penghasilan'";
    $rules = query($query);

    // Jika tidak ada aturan yang cocok, kembalikan hasil default
    if (!$rules || count($rules) === 0) {
        return [
            'nilai' => 0,
            'keterangan' => 'Tidak Diketahui'
        ];
    }

    // Inisialisasi variabel untuk menyimpan hasil perhitungan
    $total_weighted_output = 0;
    $total_membership_value = 0;

    // Proses inferensi berdasarkan aturan yang didapat
    foreach ($rules as $rule) {
        $output = $rule['nilai'];

        // Dapatkan nilai keanggotaan untuk setiap variabel input
        $membership_uts = getMembershipValue('uts', $nilai_uts);
        $membership_uas = getMembershipValue('uas', $nilai_uas);
        $membership_keaktifan = getMembershipValue('keaktifan', $keaktifan);
        $membership_penghasilan = getMembershipValue('penghasilan', $penghasilan);

        // Gunakan metode inferensi MIN, yaitu nilai terkecil dari keanggotaan
        $membership_value = min($membership_uts, $membership_uas, $membership_keaktifan, $membership_penghasilan);

        // Hitung total output berbobot dan total nilai keanggotaan
        $total_weighted_output += $membership_value * $output;
        $total_membership_value += $membership_value;
    }

    // Defuzzification: Hitung nilai akhir sebagai rata-rata berbobot
    if ($total_membership_value > 0) {
        $nilai_fuzzy = $total_weighted_output / $total_membership_value;
        $keterangan = $nilai_fuzzy >= 50 ? 'Layak' : 'Tidak Layak';
    } else {
        $nilai_fuzzy = 0;
        $keterangan = 'Tidak Diketahui';
    }

    // Return hasil fuzzy dan keterangan kelayakan
    return [
        'nilai' => $nilai_fuzzy,
        'keterangan' => $keterangan
    ];
}

// Fungsi untuk mendapatkan nilai keanggotaan (membership value)
function getMembershipValue($tipe, $nilai)
{
    switch ($tipe) {
        case 'uts':
        case 'uas':
            if ($nilai > 80) return 1;       // Keanggotaan penuh untuk kategori 'tinggi'
            if ($nilai > 60) return 0.5;     // Keanggotaan parsial untuk kategori 'sedang'
            return 0.1;                       // Keanggotaan rendah untuk kategori 'rendah'

        case 'keaktifan':
            if ($nilai > 70) return 1;       // Keanggotaan penuh untuk keaktifan tinggi
            if ($nilai > 50) return 0.5;     // Keanggotaan parsial untuk keaktifan sedang
            return 0.1;                       // Keanggotaan rendah untuk keaktifan rendah

        case 'penghasilan':
            if ($nilai < 2000000) return 1;  // Keanggotaan penuh untuk penghasilan rendah
            if ($nilai < 5000000) return 0.5; // Keanggotaan parsial untuk penghasilan sedang
            return 0.1;                       // Keanggotaan rendah untuk penghasilan tinggi

        default:
            return 0;
    }
}

function simpanHasilFuzzy($user_id, $id_siswa, $nis, $nama_siswa, $nilai_uts, $nilai_uas, $keaktifan, $penghasilan, $nilai_fuzzy, $keterangan, $dateReport)
{
    global $db;
    $dateReport = date('Y-m-d', strtotime($dateReport));

    // Escape input untuk menghindari SQL injection
    $user_id = mysqli_real_escape_string($db, $user_id);
    $nis = mysqli_real_escape_string($db, $nis);
    $nama_siswa = mysqli_real_escape_string($db, $nama_siswa);
    $nilai_uts = mysqli_real_escape_string($db, $nilai_uts);
    $nilai_uas = mysqli_real_escape_string($db, $nilai_uas);
    $keaktifan = mysqli_real_escape_string($db, $keaktifan);
    $penghasilan = mysqli_real_escape_string($db, $penghasilan);
    $nilai_fuzzy = mysqli_real_escape_string($db, $nilai_fuzzy);
    $keterangan = mysqli_real_escape_string($db, $keterangan);
    $dateReport = mysqli_real_escape_string($db, $dateReport);

    $checkQuery = "SELECT * FROM hasil_fuzzy 
                   WHERE user_id = '$user_id' 
                   AND nis = '$nis' 
                   AND nilai_uts = '$nilai_uts' 
                   AND nilai_uas = '$nilai_uas' 
                   AND keaktifan = '$keaktifan' 
                   AND penghasilan = '$penghasilan' 
                   AND nilai_fuzzy = '$nilai_fuzzy' 
                   AND keterangan = '$keterangan'";

    $checkResult = mysqli_query($db, $checkQuery);

    // Jika ada data yang sama, tidak perlu menyimpan
    if (mysqli_num_rows($checkResult) > 0) {
        return true;
    }

    // Query SQL untuk menyimpan data
    $query = "INSERT INTO hasil_fuzzy (user_id, id_siswa, nis, nama_siswa, nilai_uts, nilai_uas, keaktifan, penghasilan, nilai_fuzzy, keterangan, date_report)
              VALUES ('$user_id', '$id_siswa', '$nis', '$nama_siswa', '$nilai_uts', '$nilai_uas', '$keaktifan', '$penghasilan', '$nilai_fuzzy', '$keterangan', '$dateReport')";

    $result = mysqli_query($db, $query);

    // Cek apakah query berhasil
    if (!$result) {
        // Jika gagal, tampilkan pesan error dari MySQL
        echo "Error saat menyimpan data: " . mysqli_error($db);
        return false;
    }

    return true;
}
