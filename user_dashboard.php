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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body {
            font-family: Arial;
            background: #f1f1f1;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 1000px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        form input,
        textarea {
            width: 100%;
            margin: 5px 0;
            padding: 8px;
        }

        #map {
            height: 300px;
            margin-top: 15px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Dashboard Pengguna</h2>
        <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['username']) ?></b>!</p>

        <h3>Tambah Bisnis Baru</h3>
        <form action="save.php" method="POST">
            <input type="text" name="name" placeholder="Nama Bisnis" required>
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <input type="text" name="category" placeholder="Kategori" required>
            <input type="text" name="address" placeholder="Alamat Lengkap (misal: Jl. Sudirman No.1, Surabaya)" required>
            <button type="submit">Simpan Bisnis</button>
        </form>

        <div id="map"></div>

        <h3>Bisnis Saya</h3>
        <table>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Alamat</th>
                <th>Koordinat</th>
                <th>Aksi</th>
            </tr>
            <?php while ($b = mysqli_fetch_assoc($bisnisQuery)): ?>
                <tr>
                    <td><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['category']) ?></td>
                    <td><?= htmlspecialchars($b['address']) ?></td>
                    <td><?= $b['latitude'] ?>, <?= $b['longitude'] ?></td>
                    <td>
                        <form action="update.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($b['name']) ?>" required>
                            <input type="text" name="category" value="<?= htmlspecialchars($b['category']) ?>" required>
                            <input type="text" name="address" value="<?= htmlspecialchars($b['address']) ?>" required>
                            <button type="submit">Update</button>
                        </form>
                        <form action="delete.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <button type="submit" onclick="return confirm('Yakin?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <a href="logout.php">Logout</a>
    </div>

    <script>
        var map = L.map('map').setView([-7.25, 112.75], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        fetch('get_data.php')
            .then(res => res.json())
            .then(data => {
                data.forEach(b => {
                    if (b.id_user == <?= $id_user ?>) {
                        L.marker([b.latitude, b.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${b.name}</b><br>${b.description}<br>${b.category}`);
                    }
                });
            });
    </script>

</body>

</html>