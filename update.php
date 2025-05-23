<?php
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $category = $_POST['category'];
  $street = $_POST['street'];
  $zipcode = $_POST['zipcode'];
  $city = $_POST['city'];
  $province = $_POST['province'];

  $full_address = "$street, $zipcode, $city, $province";
  $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($full_address);

  $opts = ['http' => ['header' => "User-Agent: pelok-usaha\r\n"]];
  $context = stream_context_create($opts);
  $geoData = file_get_contents($url, false, $context);
  $geo = json_decode($geoData, true);

  if (!empty($geo)) {
    $latitude = $geo[0]['lat'];
    $longitude = $geo[0]['lon'];

    $stmt = $koneksi->prepare("UPDATE businesses SET name=?, category=?, address=?, latitude=?, longitude=? WHERE id_business=?");
    $stmt->bind_param("ssssdi", $name, $category, $full_address, $latitude, $longitude, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: user_dashboard.php");
    exit;
  } else {
    echo "Alamat tidak valid.";
  }
}
