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
    <div class="container">
        <div class="left-panel">
            <div class="logo">
                <img src="images/logo2.png" alt="Logo">
            </div>
            <h2>Selamat Datang!</h2>
            <p>Kalo anda sudah punya akun silahkan login pada akun anda</p>
            <a href="login.php" class="btn">SIGN IN</a>
        </div>

        <div class="right-panel">
            <h2>Buat Akun</h2>
            <form method="post" action="">
                <label>Username:</label>
                <input type="text" name="username" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <label>Role:</label>
                <select name="role" id="role" onchange="toggleKodeAdmin()" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <div id="kodeAdminContainer" style="display: none;">
                    <label>Kode Admin:</label>
                    <input type="text" name="kode_admin" id="kode_admin">
                </div>

                <button type="submit">SIGN UP</button>
            </form>
        </div>
    </div>

    <script>
        function toggleKodeAdmin() {
            const role = document.getElementById('role').value;
            const kodeAdminDiv = document.getElementById('kodeAdminContainer');
            kodeAdminDiv.style.display = (role === 'admin') ? 'block' : 'none';
        }
    </script>

</div>