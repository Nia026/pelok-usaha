<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$currentUsername = $_SESSION['username'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$currentUsername'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "Data tidak ditemukan.";
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $fields = [];
  $params = [];
  $types = "";

  if (!empty($username) && $username !== $data['username']) {
    $fields[] = "username = ?";
    $params[] = $username;
    $types .= "s";
  }

  if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $fields[] = "password = ?";
    $params[] = $hashedPassword;
    $types .= "s";
  }

  if (!empty($fields)) {
    $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    $types .= "s";
    $params[] = $currentUsername;
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $stmt->close();

    // Update session jika username berubah
    if (!empty($username) && $username !== $currentUsername) {
      $_SESSION['username'] = $username;
    }

    echo "<script>
      alert('Profil berhasil diperbarui!');
      window.location.href = 'user_dashboard.php';
    </script>";
    exit;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Profil</title>
  <link rel="stylesheet" href="./assets/css/dashboard_user.css">
  <link rel="stylesheet" href="./assets/css/add_businesses.css">
</head>

<body>
  <nav class="navbar">
    <img src="images/logo2.png" alt="Pelok Usaha Logo" class="logo">
    <div class="nav-links">
      <a href="index.php" class="btn btn-secondary">Logout</a>
    </div>
  </nav>

  <div class="container">
    <h2>Edit Profil</h2>
    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

      <label>Password Baru</label>
      <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti password">

      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      <a href="user_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>

  <footer class="footer">
    <p>© 2025 PelokUsaha. All rights reserved.</p>
  </footer>
</body>

</html>