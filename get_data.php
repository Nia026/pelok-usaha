<?php
include 'config/database.php';

// $query = mysqli_query($koneksi, "SELECT * FROM businesses");
$query = mysqli_query($koneksi, "
  SELECT b.*, u.username 
  FROM businesses b
  JOIN users u ON b.id_user = u.id_user
");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
