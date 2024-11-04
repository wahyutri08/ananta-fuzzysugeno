<?php
// Mendapatkan halaman saat ini dari URL
$current_page = basename($_SERVER['REQUEST_URI']);

// Halaman-halaman yang berada di dalam Master Data
$master_data_pages = ['data_siswa', 'data_variabel', 'rule_fuzzy', 'penilaian',];
$keputusan = ['proses'];
$settings_page = ['profile', 'change_password'];
$id = $_SESSION["id"];
$role = $_SESSION["role"];
$user = query("SELECT * FROM users WHERE id = $id")[0];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../dashboard" class="brand-link">
        <img src="../assets/dist/img/<?= $user['avatar']; ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $user['nama']; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <!-- <img src="../../dist/img/user2-160x160." class="img-circle elevation-2" alt="User Image"> -->
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user["nama"]; ?></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="../dashboard" class="nav-link <?= ($current_page == 'dashboard' || $current_page == 'dashboard.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data Menu -->
                <li class="nav-item <?= (in_array($current_page, $master_data_pages)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $master_data_pages)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../data_siswa" class="nav-link <?= ($current_page == 'data_siswa' ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Siswa</p>
                            </a>
                        </li>
                        <?php
                        if ($user['role'] == 'Admin') {
                            echo '
                            <li class="nav-item">
                                <a href="../data_variabel" class="nav-link ' . ($current_page == 'data_variabel' ? 'active' : '') . '">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Variabel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../rule_fuzzy" class="nav-link ' . ($current_page == 'rule_fuzzy' ? 'active' : '') . '">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rule Fuzzy</p>
                                </a>
                            </li>
                            ';
                        } else {
                            echo '';
                        }
                        ?>
                        <li class="nav-item">
                            <a href="../penilaian" class="nav-link <?= ($current_page == 'penilaian') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penilaian</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Proses Beasiswa -->
                <!-- <li class="nav-header">PROSES</li> -->
                <li class="nav-item <?= (in_array($current_page, $keputusan)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $keputusan)) ? 'active' : '' ?>">
                        <i class="nav-icon fa-solid fa-gears"></i>
                        <p>
                            Keputusan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../proses" class="nav-link <?= ($current_page == 'proses') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Proses</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Laporan Hasil Analisa -->
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="../hasil_fuzzy" class="nav-link <?= ($current_page == 'hasil_fuzzy') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Laporan Hasil Analisa</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item <?= (in_array($current_page, $settings_page)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $settings_page)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../profile" class="nav-link <?= ($current_page == 'profile') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../change_password" class="nav-link <?= ($current_page == 'change_password') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Management -->
                <?php
                if ($user['role'] == 'Admin') {
                    echo '
                    <li class="nav-item">
                        <a href="../user_management" class="nav-link ' . ($current_page == 'user_management' ? 'active' : '') . '">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    ';
                } else {
                    echo '';
                }
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>