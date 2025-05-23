<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

// Ambil id_user dari session
$username = $_SESSION['username'];
$userQuery = mysqli_query($koneksi, "SELECT id_user FROM users WHERE username = '$username'");
$userData = mysqli_fetch_assoc($userQuery);
$id_user = $userData['id_user'];

$name = $_POST['name'];
$description = $_POST['description'];
$category = $_POST['category'];
$street = $_POST['street'];
$zipcode = $_POST['zipcode'];
$city = $_POST['city'];
$province = $_POST['province'];

$fullAddress = "$street $zipcode $city $province";

// Geocoding menggunakan Nominatim
$url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($fullAddress);
$opts = [
  "http" => [
    "header" => "User-Agent: pelok-gis-app"
  ]
];
$context = stream_context_create($opts);
$response = file_get_contents($url, false, $context);
$data = json_decode($response, true);

if (!empty($data)) {
  $latitude = $data[0]['lat'];
  $longitude = $data[0]['lon'];

  $insert = mysqli_query($koneksi, "INSERT INTO businesses (name, description, category, address, latitude, longitude, id_user)
        VALUES ('$name', '$description', '$category', '$fullAddress', '$latitude', '$longitude', '$id_user')");

  if ($insert) {
    header("Location: user_dashboard.php");
    exit;
  } else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
  }
} else {
  echo "Alamat tidak ditemukan oleh Nominatim.";
}
