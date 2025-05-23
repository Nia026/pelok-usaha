<?php
session_start();
include 'config/database.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Cek data user di tabel users
$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: dashboard.php");
} else {
    echo "Login gagal! Username atau password salah.";
}
?>