<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
  echo "ID tidak ditemukan.";
  exit;
}

// Ambil data lama dari database
$query = mysqli_query($koneksi, "SELECT * FROM businesses WHERE id_business = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "Data tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $category = $_POST['category'] ?? '';
  $description = $_POST['description'] ?? '';
  $street = $_POST['street'] ?? '';
  $zipcode = $_POST['zipcode'] ?? '';
  $city = $_POST['city'] ?? '';
  $province = $_POST['province'] ?? '';

  $fields = [];
  $params = [];
  $types = "";

  if (!empty($name) && $name !== $data['name']) {
    $fields[] = "name = ?";
    $params[] = $name;
    $types .= "s";
  }

  if (!empty($category) && $category !== $data['category']) {
    $fields[] = "category = ?";
    $params[] = $category;
    $types .= "s";
  }

  if (!empty($description) && $description !== $data['description']) {
    $fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
  }

  $new_address = trim("$street, $zipcode, $city, $province");
  $old_address = trim($data['address']);

  if (!empty($street) && !empty($zipcode) && !empty($city) && !empty($province) && $new_address !== $old_address) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($new_address);
    $opts = ['http' => ['header' => "User-Agent: pelok-usaha/1.0"]];
    $context = stream_context_create($opts);
    sleep(1);
    $geoData = @file_get_contents($url, false, $context);
    $geo = json_decode($geoData, true);

    if (!empty($geo)) {
      $latitude = $geo[0]['lat'];
      $longitude = $geo[0]['lon'];

      $fields[] = "address = ?";
      $params[] = $new_address;
      $types .= "s";

      $fields[] = "latitude = ?";
      $params[] = $latitude;
      $types .= "d";

      $fields[] = "longitude = ?";
      $params[] = $longitude;
      $types .= "d";
    } else {
      echo "<script>alert('Alamat tidak valid atau tidak ditemukan');</script>";
    }
  }

  if (!empty($fields)) {
    $sql = "UPDATE businesses SET " . implode(", ", $fields) . " WHERE id_business = ?";
    $stmt = $koneksi->prepare($sql);
    $types .= "i";
    $params[] = $id;
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();

    echo "<script>
      alert('Data berhasil diperbarui!');
      window.location.href = 'user_dashboard.php';
    </script>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Usaha</title>
  <link rel="stylesheet" href="./assets/css/dashboard_user.css">
  <link rel="stylesheet" href="./assets/css/add_businesses.css">
</head>

<body>
  <nav class="navbar">
    <img src="images/logo2.png" alt="Pelok Usaha Logo" class="logo">
    <div class="nav-links">
      <a href="edit_profile_user.php" class="btn btn-profile">Edit Profile</a>
      <a href="index.php" class="btn btn-secondary">Logout</a>
    </div>
  </nav>

  <div class="container">
    <h2>Edit Usaha</h2>
    <form method="POST">
      <label>Nama Usaha</label>
      <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

      <label>Kategori</label>
      <input type="text" name="category" value="<?= htmlspecialchars($data['category']) ?>" required>

      <label>Deskripsi</label>
      <textarea name="description" required><?= htmlspecialchars($data['description']) ?></textarea>

      <label>Nama Jalan</label>
      <input type="text" name="street" placeholder="Kosongkan jika tidak ingin ubah">

      <label>Kode Pos</label>
      <input type="text" name="zipcode" placeholder="Kosongkan jika tidak ingin ubah">

      <label>Kota</label>
      <input type="text" name="city" placeholder="Kosongkan jika tidak ingin ubah">

      <label>Provinsi</label>
      <input type="text" name="province" placeholder="Kosongkan jika tidak ingin ubah">

      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="user_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

  <footer class="footer">
    <p>© 2025 PelokUsaha. All rights reserved.</p>
  </footer>
</body>

</html>