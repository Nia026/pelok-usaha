<?php
include 'config/database.php';

$query = mysqli_query($koneksi, "SELECT * FROM businesses");
$data = [];

while ($row = mysqli_fetch_assoc($query)) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
