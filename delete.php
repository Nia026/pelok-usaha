<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$id = $_POST['id'];

$delete = mysqli_query($koneksi, "DELETE FROM businesses WHERE id_business='$id'");

if ($delete) {
  header("Location: user_dashboard.php");
  exit;
} else {
  echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
