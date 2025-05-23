<?php
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;
  $name = $_POST['name'] ?? '';
  $category = $_POST['category'] ?? '';
  $description = $_POST['description'] ?? '';
  $street = $_POST['street'] ?? '';
  $zipcode = $_POST['zipcode'] ?? '';
  $city = $_POST['city'] ?? '';
  $province = $_POST['province'] ?? '';

  if (!$id) {
    echo "ID bisnis tidak ditemukan.";
    exit;
  }

  // Ambil data lama
  $query = mysqli_query($koneksi, "SELECT * FROM businesses WHERE id_business = $id");
  $old = mysqli_fetch_assoc($query);
  if (!$old) {
    echo "Data bisnis tidak ditemukan.";
    exit;
  }

  // Persiapkan array untuk field yang akan diupdate
  $fields = [];
  $params = [];
  $types = "";

  // Cek perubahan nama
  if ($name !== $old['name']) {
    $fields[] = "name = ?";
    $params[] = $name;
    $types .= "s";
  }

  // Cek perubahan kategori
  if ($category !== $old['category']) {
    $fields[] = "category = ?";
    $params[] = $category;
    $types .= "s";
  }

  // Cek perubahan deskripsi
  if ($description !== $old['description']) {
    $fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
  }

  // Bangun alamat baru
  $new_address = trim("$street, $zipcode, $city, $province");
  $old_address = trim($old['address']);

  // Jika alamat berubah, lakukan geocoding
  if ($new_address !== $old_address && $street && $zipcode && $city && $province) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($new_address);
    $opts = ['http' => ['header' => "User-Agent: pelok-usaha/1.0"]];
    $context = stream_context_create($opts);
    sleep(1); // Hindari rate limit

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
      echo "Alamat tidak valid atau tidak ditemukan.";
      exit;
    }
  }

  // Jika tidak ada field yang berubah
  if (empty($fields)) {
    header("Location: dashboard_user.php");
    exit;
  }

  // Siapkan query update dinamis
  $sql = "UPDATE businesses SET " . implode(', ', $fields) . " WHERE id_business = ?";
  $stmt = $koneksi->prepare($sql);
  $types .= "i"; // untuk id
  $params[] = $id;

  // Gunakan call_user_func_array untuk bind_param dinamis
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $stmt->close();

  header("Location: dashboard_user.php");
  exit;
}
