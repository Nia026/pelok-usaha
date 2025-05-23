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
  <link rel="stylesheet" href="./assets/css/add_businesses.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <img src="images/logo2.png" alt="Pelok Usaha Logo" class="logo">
    <div class="nav-links">
      <a href="edit_profile_user.php" class="btn btn-secondary">Edit Profile</a>
      <a href="index.php" class="btn btn-secondary">Logout</a>
    </div>
  </nav>

  <!-- Container Utama -->
  <div class="container">
    <div class="header">
      <h2>Tambah Usaha Baru</h2>
      <a href="user_dashboard.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
    </div>

    <form action="save.php" method="POST">
      <label for="name">Nama Usaha</label>
      <input type="text" id="name" name="name" required>

      <label for="description">Deskripsi</label>
      <textarea id="description" name="description" required></textarea>

      <label for="category">Kategori</label>
      <input type="text" id="category" name="category" required>

      <label for="street">Nama Jalan</label>
      <input type="text" id="street" name="street" required>

      <label for="zipcode">Kode Pos</label>
      <input type="text" id="zipcode" name="zipcode" required>

      <label for="city">Kota</label>
      <input type="text" id="city" name="city" required>

      <label for="province">Provinsi (Bahasa Inggris)</label>
      <input type="text" id="province" name="province" required>

      <button type="submit" class="btn btn-primary">Simpan Usaha</button>
    </form>
  </div>

  <!-- Footer -->
  <footer>
    <p>© <?= date("Y") ?> Pelok Usaha. All rights reserved.</p>
  </footer>
</body>

</html>