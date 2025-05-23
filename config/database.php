<?php
$host = "localhost";
$user = "root";
$pass = ""; // kosong kalau default XAMPP
$db   = "pelok"; // sesuai nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>