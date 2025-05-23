<?php
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];
    $kode_admin = isset($_POST['kode_admin']) ? $_POST['kode_admin'] : '';

    // Cek kode admin jika role admin
    if ($role === 'admin' && $kode_admin !== 'PND122622') {
        echo "<script>alert('Kode admin salah!'); window.location='register.php';</script>";
        exit;
    }

    // Amankan password (kalau mau pakai hash tinggal buka komentar)
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan!'); window.location='register.php';</script>";
    } else {
        $simpan = mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')");
        if ($simpan) {
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Gagal mendaftar.');</script>";
        }
    }
}
?>

<link rel="stylesheet" href="assets/css/register.css">
<div class="container">
<h2>Form Register</h2>
<form method="post" action="">
    <div class="logo">
        <img src="logo.png" alt="Logo">
        <h1>PELOK USAHA</h1>
    </div>
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <label>Role:</label><br>
    <select name="role" id="role" onchange="toggleKodeAdmin()" required>
        <option value="">-- Pilih Role --</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select><br><br>

    <div id="kodeAdminContainer" style="display: none;">
        <label>Kode Admin:</label><br>
        <input type="text" name="kode_admin" id="kode_admin"><br><br>
    </div>

    <button type="submit">Daftar</button>
</form>

<p>Sudah punya akun? <a href="login.php">Login di sini</a></p>

<script>
function toggleKodeAdmin() {
    const role = document.getElementById('role').value;
    const kodeAdminDiv = document.getElementById('kodeAdminContainer');

    if (role === 'admin') {
        kodeAdminDiv.style.display = 'block';
    } else {
        kodeAdminDiv.style.display = 'none';
    }
}
</script>
</div>