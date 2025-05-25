<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$userQuery = mysqli_query($koneksi, "SELECT id_user FROM users WHERE username = '$username'");
$userData = mysqli_fetch_assoc($userQuery);
$id_user = $userData['id_user'];

$bisnisQuery = mysqli_query($koneksi, "SELECT * FROM businesses WHERE id_user = $id_user");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="./assets/css/dashboard_user.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>
    <nav class="navbar">
        <img src="images/logo2.png" alt="Pelok Usaha Logo" class="logo">
        <div class="nav-links">
            <a href="edit_profile_user.php" class="btn btn-profile">Edit Profile</a>
            <a href="bantuan.php" class="btn btn-bantuan">Bantuan</a>
            <a href="index.php" class="btn btn-secondary">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</h2>
            <a href="add_businesses.php" class="btn btn-primary">+ Tambah Usaha</a>
        </div>

        <div id="map"></div>

        <h3>Daftar Usaha Anda</h3>
        <table class="business-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($b = mysqli_fetch_assoc($bisnisQuery)): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= htmlspecialchars($b['description']) ?></td>
                        <td><?= htmlspecialchars($b['category']) ?></td>
                        <td><?= htmlspecialchars($b['address']) ?></td>
                        <td class="form-btn">
                            <form action="edit_businesses.php" method="GET" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                                <button class="btn btn-edit" type="submit">Edit</button>
                            </form>
                            <form action="delete.php" method="POST" style="display:inline;"
                                onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                                <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                                <button class="btn btn-delete" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>© <?= date("Y") ?> Pelok Usaha. All rights reserved.</p>
    </footer>

    <script>
        var map = L.map('map').setView([-7.250445, 112.768845], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(function(b) {
                    if (b.id_user == <?= $id_user ?>) {
                        L.marker([b.latitude, b.longitude]).addTo(map)
                            .bindPopup(`<b>${b.name}</b><br>${b.description}<br>${b.category}`);
                    }
                });
            });
    </script>
</body>

</html>