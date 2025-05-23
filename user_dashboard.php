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
            padding: 20px;
            background: #f1f1f1;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
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
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        form input,
        textarea {
            width: 100%;
            margin: 5px 0;
            padding: 8px;
        }

        form button {
            padding: 8px 12px;
        }

        #map {
            height: 300px;
            margin-top: 15px;
            border: 1px solid #ccc;
        }

        .logout-link {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Dashboard Pengguna</h2>
        <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['username']) ?></b>! Anda login sebagai <b>User</b>.</p>

        <h3>Tambah Bisnis Baru</h3>
        <form action="save.php" method="POST">
            <input type="text" name="name" placeholder="Nama Bisnis" required>
            <textarea name="description" placeholder="Deskripsi" required></textarea>
            <input type="text" name="category" placeholder="Kategori" required>
            <input type="text" name="street" placeholder="Nama Jalan" required>
            <input type="text" name="postalcode" placeholder="Kode Pos" required>
            <input type="text" name="city" placeholder="Kota" required>
            <input type="text" name="province" placeholder="Provinsi (Bahasa Inggris)" required>
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
            <?php while ($b = mysqli_fetch_assoc($bisnisQuery)):
                // Pecah address
                $address_parts = explode(',', $b['address']);
                $street = trim($address_parts[0] ?? '');
                $zipcode = trim($address_parts[1] ?? '');
                $city = trim($address_parts[2] ?? '');
                $province = trim($address_parts[3] ?? '');
            ?>
                <tr>
                    <td><?= htmlspecialchars($b['name']) ?></td>
                    <td><?= htmlspecialchars($b['category']) ?></td>
                    <td><?= htmlspecialchars($b['address']) ?></td>
                    <td><?= $b['latitude'] ?>, <?= $b['longitude'] ?></td>
                    <td>
                        <form action="update.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($b['name']) ?>" required>
                            <input type="text" name="category" value="<?= htmlspecialchars($b['category']) ?>" required>
                            <input type="text" name="street" value="<?= htmlspecialchars($street) ?>" required placeholder="Jalan">
                            <input type="text" name="zipcode" value="<?= htmlspecialchars($zipcode) ?>" required placeholder="Kode Pos">
                            <input type="text" name="city" value="<?= htmlspecialchars($city) ?>" required placeholder="Kota">
                            <input type="text" name="province" value="<?= htmlspecialchars($province) ?>" required
                                placeholder="Provinsi">
                            <button type="submit">Update</button>
                        </form>
                        <form action="delete.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?= $b['id_business'] ?>">
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus bisnis ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <!-- <a href="logout.php" class="logout-link">Logout</a> -->
        <a href="index.php" class="logout-link">Logout</a>
    </div>

    <script>
        var map = L.map('map').setView([-7.250445, 112.768845], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
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