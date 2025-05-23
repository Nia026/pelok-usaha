<?php
include 'config/database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah data cocok di database
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Login berhasil, simpan session
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        // Arahkan ke halaman sesuai role
        if ($data['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>

<link rel="stylesheet" href="assets/css/login.css">
<div class="container">
  <div class="container">
    <div class="left-panel">
      <div class="logo">
        <img src="images/logo3.png" alt="Logo">
      </div>
      <h2>Hello, Friend!</h2>
      <p>Belum punya akun? Silahkan gabung disini</p>
      <a href="register.php" class="btn">SIGN UP</a>
    </div>

    <div class="right-panel">
      <h2>Login Akun</h2>
      <form method="post" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">LOGIN</button>
      </form>
    </div>
  </div>

</div>