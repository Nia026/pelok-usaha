<?php
include 'config/database.php';

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

// Handle update
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

  // Update nama
  if (!empty($name) && $name !== $data['name']) {
    $fields[] = "name = ?";
    $params[] = $name;
    $types .= "s";
  }

  // Update kategori
  if (!empty($category) && $category !== $data['category']) {
    $fields[] = "category = ?";
    $params[] = $category;
    $types .= "s";
  }

  // Update deskripsi
  if (!empty($description) && $description !== $data['description']) {
    $fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
  }

  // Cek apakah alamat diisi dan berbeda
  $new_address = trim("$street, $zipcode, $city, $province");
  $old_address = trim($data['address']);

  if (!empty($street) && !empty($zipcode) && !empty($city) && !empty($province) && $new_address !== $old_address) {
    // Geocoding alamat baru
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($new_address);
    $opts = ['http' => ['header' => "User-Agent: pelok-usaha/1.0"]];
    $context = stream_context_create($opts);
    sleep(1); // rate limiting

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
      echo "<p style='color:red;'>Alamat tidak valid atau tidak ditemukan.</p>";
    }
  }

  // Jika ada yang diupdate, jalankan query
  if (!empty($fields)) {
    $sql = "UPDATE businesses SET " . implode(", ", $fields) . " WHERE id_business = ?";
    $stmt = $koneksi->prepare($sql);
    $types .= "i";
    $params[] = $id;
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();

    // Redirect ke dashboard_user.php
    echo "<script>window.location.href = 'user_dashboard.php';</script>";
    exit;
  }
}
?>

<!-- FORM HTML -->
<!DOCTYPE html>
<html>

<head>
  <title>Edit Usaha</title>
</head>

<body>
  <h2>Edit Usaha</h2>
  <form method="POST">
    <label>Nama Usaha</label>
    <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>"><br>

    <label>Kategori</label>
    <input type="text" name="category" value="<?= htmlspecialchars($data['category']) ?>"><br>

    <label>Deskripsi</label>
    <textarea name="description"><?= htmlspecialchars($data['description']) ?></textarea><br>

    <label>Nama Jalan</label>
    <input type="text" name="street" placeholder="Kosongkan jika tidak ingin ubah"><br>

    <label>Kode Pos</label>
    <input type="text" name="zipcode" placeholder="Kosongkan jika tidak ingin ubah"><br>

    <label>Kota</label>
    <input type="text" name="city" placeholder="Kosongkan jika tidak ingin ubah"><br>

    <label>Provinsi</label>
    <input type="text" name="province" placeholder="Kosongkan jika tidak ingin ubah"><br><br>

    <button type="submit" class="btn btn-success">Update Usaha</button>
    <a href="user_dashboard.php" class="btn btn-danger">Kembali</a>
  </form>
</body>

</html>