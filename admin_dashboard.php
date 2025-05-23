<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<link rel="stylesheet" href="assets/css/dashboard.css">
<div class="container">
    <h2>Dashboard Admin</h2>
    
    <p>Selamat datang, <b><?= $_SESSION['username'] ?></b>! Anda login sebagai <b>Admin</b>.</p>

    <div style="margin-top: 20px;">
        <h3>Manajemen Data</h3>
        <ul>
            <li><a href="#">Lihat semua pengguna</a></li>
            <li><a href="#">Lihat semua bisnis</a></li>
            <li><a href="#">Kelola kategori</a></li>
        </ul>
    </div>

    <div style="margin-top: 30px;">
        <h3>Statistik</h3>
        <div style="display: flex; gap: 10px;">
            <div style="flex:1; padding: 15px; background: #e6f7ff; border-radius: 8px;">
                <h4>Total User</h4>
                <p>12</p> <!-- ganti dinamis nanti -->
            </div>
            <div style="flex:1; padding: 15px; background: #e6ffe6; border-radius: 8px;">
                <h4>Total Bisnis</h4>
                <p>8</p>
            </div>
        </div>
    </div>

    <a href="logout.php" class="logout-link">Logout</a>
</div>