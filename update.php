<?php
include 'config/database.php';
session_start();

$id = $_POST['id'];
$name = $_POST['name'];
$category = $_POST['category'];
$address = $_POST['address'];

// Geocode ulang jika alamat berubah
$encodedAddress = urlencode($address);
$url = "https://nominatim.openstreetmap.org/search?q=$encodedAddress&format=json&limit=1";

$options = ['http' => ['header' => "User-Agent: MyGISApp/1.0 (niarfebriar@gmail.com)\r\n"]];
$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$data = json_decode($response, true);

if (!empty($data)) {
  $lat = $data[0]['lat'];
  $lon = $data[0]['lon'];

  $query = "UPDATE businesses SET name='$name', category='$category', address='$address',
              latitude='$lat', longitude='$lon' WHERE id_business=$id";
  mysqli_query($koneksi, $query);
}

header("Location: user_dashboard.php");
