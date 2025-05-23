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
    <div class="container">
        <div class="header">
            <h2>Dashboard Pengguna</h2>
            <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
            <a href="add_businesses.php" class="btn btn-primary">+ Tambah Usaha</a>
            <a href="index.php" class="btn btn-secondary">Logout</a>
        </div>

        <div id="map"></div>

        <h3>Usaha Anda</h3>
        <div class="card-list">
            <?php while ($b = mysqli_fetch_assoc($bisnisQuery)): ?>
                <div class="card">
                    <h4><?= htmlspecialchars($b['name']) ?></h4>
                    <p><strong>Kategori:</strong> <?= htmlspecialchars($b['category']) ?></p>
                    <p><strong>Alamat:</strong> <?= htmlspecialchars($b['address']) ?></p>
                    <p><strong>Koordinat:</strong> <?= $b['latitude'] ?>, <?= $b['longitude'] ?></p>
                    <div class="btn-group">
                        <form action="edit_businesses.php" method="GET">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <button class="btn btn-edit" type="submit">Edit</button>
                        </form>

                        <form action="delete.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus bisnis ini?')">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <button class="btn btn-delete" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

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