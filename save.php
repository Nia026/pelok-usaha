<?php
include 'config/database.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username'];
$userQuery = mysqli_query($koneksi, "SELECT id_user FROM users WHERE username = '$username'");
$userData = mysqli_fetch_assoc($userQuery);
$id_user = $userData['id_user'];

$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$address = $_POST['address'];

$encodedAddress = urlencode($address);
$url = "https://nominatim.openstreetmap.org/search?q=$encodedAddress&format=json&limit=1";

$options = ['http' => ['header' => "User-Agent: MyGISApp/1.0 (niarfebriar@gmail.com)\r\n"]];
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$data = json_decode($response, true);

if (!empty($data)) {
  $lat = $data[0]['lat'];
  $lon = $data[0]['lon'];

  $insert = mysqli_query($koneksi, "INSERT INTO businesses (name, description, category, address, latitude, longitude, id_user)
        VALUES ('$name', '$description', '$category', '$address', '$lat', '$lon', '$id_user')");

  if ($insert) {
    header("Location: user_dashboard.php");
  } else {
    echo "Gagal menyimpan data.";
  }
} else {
  echo "Alamat tidak ditemukan. Pastikan alamat lengkap.";
}
