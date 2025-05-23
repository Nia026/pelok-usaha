<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="assets/css/dashboard.css">
<div class="container">
    <h2>Dashboard Pengguna</h2>
    
    <p>Selamat datang, <b><?= $_SESSION['username'] ?></b>! Anda login sebagai <b>User</b>.</p>

    <div style="margin-top: 20px;">
        <h3>Bisnis Saya</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr style="background-color:#f0f0f0;">
                <th>Nama Bisnis</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <td>Contoh Toko</td>
                <td>Kuliner</td>
                <td>-7.2575, 112.7521</td>
                <td>
                    <a href="#">Edit</a> | <a href="#">Hapus</a>
                </td>
            </tr>
            <!-- Tambahkan baris lain dari database -->
        </table>
        <br>
        <a href="#">➕ Tambah Bisnis Baru</a>
    </div>

    <a href="logout.php" class="logout-link">Logout</a>
</div>