<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newName = $_POST['username'];
  $newEmail = $_POST['email'];
  $newPassword = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

  $update = mysqli_query($koneksi, "
        UPDATE users 
        SET username = '$newName', password = '$newPassword' 
        WHERE id_user = '{$user['id_user']}'
    ");

  if ($update) {
    $_SESSION['username'] = $newName;
    header("Location: admin_dashboard.php");
    exit;
  } else {
    $error = "Gagal memperbarui profil.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Profil Admin</title>
  <link rel="stylesheet" href="./assets/css/admin_dashboard.css">
  <link rel="stylesheet" href="./assets/css/edit_profile.css">
</head>

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>Admin Panel</h2>
      <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <div class="main">
      <!-- Navbar -->
      <nav class="navbar">
        <h1>Edit Profil</h1>
        <span class="user-count">👤 Logged in as: <?= htmlspecialchars($_SESSION['username']) ?></span>
      </nav>

      <!-- Content -->
      <section class="content">
        <form method="POST" class="edit-form">
          <?php if (!empty($error)) : ?>
          <p class="error"><?= $error ?></p>
          <?php endif; ?>
          <label for="username">Nama Pengguna:</label>
          <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

          <label for="password">Password Baru:</label>
          <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah">

          <button type="submit">Simpan Perubahan</button>
        </form>
      </section>

      <!-- Footer -->
      <footer class="footer">
        <p>© 2025 PelokUsaha Admin Panel</p>
      </footer>
    </div>
  </div>
</body>

</html>