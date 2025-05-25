<?php
session_start();
include 'config/database.php';

// Cek login & role
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['user', 'admin'])) {
  header("Location: login.php");
  exit;
}

// Ambil ID dari POST (user) atau GET (admin)
$id = $_POST['id'] ?? $_GET['id'] ?? null;

// Redirect default
$redirect = $_GET['redirect'] ?? 'user_dashboard.php';

if ($id) {
  $delete = mysqli_query($koneksi, "DELETE FROM businesses WHERE id_business='$id'");
  if ($delete) {
    header("Location: $redirect");
    exit;
  } else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
  }
} else {
  echo "ID bisnis tidak ditemukan.";
}
