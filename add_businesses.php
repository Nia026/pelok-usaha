<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Tambah Usaha</title>
  <link rel="stylesheet" href="./assets/css/dashboard_user.css">
</head>

<body>
  <div class="container">
    <h2>Tambah Usaha Baru</h2>
    <form action="save.php" method="POST">
      <label>Nama Usaha</label>
      <input type="text" name="name" required>

      <label>Deskripsi</label>
      <textarea name="description" required></textarea>

      <label>Kategori</label>
      <input type="text" name="category" required>

      <label>Nama Jalan</label>
      <input type="text" name="street" required>

      <label>Kode Pos</label>
      <input type="text" name="zipcode" required>

      <label>Kota</label>
      <input type="text" name="city" required>

      <label>Provinsi (Bahasa Inggris)</label>
      <input type="text" name="province" required>

      <button type="submit" class="btn btn-primary">Simpan Usaha</button>
      <a href="dashboard_user.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>

</html>