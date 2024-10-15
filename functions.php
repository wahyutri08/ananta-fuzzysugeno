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

    mysqli_query($db, "INSERT INTO users VALUES('', '$username','$email', '$nama', '$password', '$role', '$avatar')");
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

    move_uploaded_file($tmpName, '../dist/img/' . $namaFileBaru);

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

    // Menambahkan data siswa baru
    $query = "INSERT INTO siswa 
              VALUES ('', '$nis', '$nama_siswa', '$alamat', '$tanggal_lahir', '$kelas', '$jenis_kelamin', '$no_telfon', '$email')";
    mysqli_query($db, $query);

    // Mengembalikan jumlah baris yang terpengaruh
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

function dataPostNilai($postData, $getData)
{
    foreach ($postData as $key => $value) {
        if ($key == 'id_siswa' || $key == 'submit') {
            continue;
        }

        // Tambahkan nama variabel ke dalam array
        $querySelect = query("SELECT * FROM penilaian WHERE id_variabel = " . $key . " AND id_siswa = " . $getData['id_siswa']);

        if (count($querySelect) > 0) {
            editNilai($postData, $getData, $key);
        } else {
            tambahNilai($postData, $getData, $key);
        }
    }
}

function editNilai($post, $get, $key)
{
    global $db;
    $query = "UPDATE penilaian SET 
    nilai = " . $post[$key] . " WHERE id_variabel = " . $key . " AND id_siswa = " . $get['id_siswa'];
    mysqli_query($db, $query);

    return mysqli_affected_rows($db);
}

function tambahNilai($post, $get, $key)
{
    global $db;

    $query = "INSERT INTO penilaian VALUES 
    (
      '', 
       " . $get['id_siswa'] . ",
       " . $key . ",
       $post[$key]
    )";

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
    // // Ambil data dari POST dan sanitasi
    $nilai_uts = mysqli_real_escape_string($db, $data['nilai_1']);
    $nilai_uas = mysqli_real_escape_string($db, $data['nilai_2']);
    $nilai_keaktifan = mysqli_real_escape_string($db, $data['nilai_3']);
    $nilai_penghasilan = mysqli_real_escape_string($db, $data['nilai_4']);
    $nilai = mysqli_real_escape_string($db, $data['nilai']); // Pastikan nilai ini ada dalam form

    // Cek apakah data yang sama sudah ada di database
    $checkQuery = "SELECT COUNT(*) as count FROM rule_fuzzy 
                   WHERE nilai_uts = '$nilai_uts' 
                   AND nilai_uas = '$nilai_uas' 
                   AND nilai_keaktifan = '$nilai_keaktifan' 
                   AND nilai_penghasilan = '$nilai_penghasilan'";

    $result = mysqli_query($db, $checkQuery);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        // Jika data sudah ada, return 0 atau pesan error
        return 0; // Data tidak dimasukkan karena sudah ada
    } else {
        // Query untuk memasukkan data ke tabel jika belum ada
        $query = "INSERT INTO rule_fuzzy (nilai_uts, nilai_uas, nilai_keaktifan, nilai_penghasilan, nilai) 
                  VALUES ('$nilai_uts', '$nilai_uas', '$nilai_keaktifan', '$nilai_penghasilan', '$nilai')";
        mysqli_query($db, $query);

        // Mengembalikan jumlah baris yang terpengaruh
        return mysqli_affected_rows($db);
    }
}

function is_user_active($id)
{
    global $db;

    $result = mysqli_query($db, "SELECT COUNT(*) AS count FROM users WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $count = $row["count"];
        if ($count > 0) {
            return true;
        }
    } else {
        return false;
    }
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
