<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

include 'config/database.php';
$userCountQuery = mysqli_query($koneksi, "SELECT COUNT(*) as total_users FROM users WHERE role = 'user'");
$userCount = mysqli_fetch_assoc($userCountQuery)['total_users'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>
        Dashboard Admin
    </title>
    <link rel="stylesheet" href="./assets/css/admin_dashboard.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('get_data.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector("#usahaTable tbody");
                    data.forEach((row, index) => {
                        const tr = document.createElement("tr");
                        tr.innerHTML = `
              <td>${index + 1}</td>
              <td>${row.name}</td>
              <td>${row.description}</td>
              <td>${row.address}</td>
              <td>${row.category}</td>
              <td>${row.username}</td>
            `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        });
    </script>
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
                <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
                <span class="user-count">👥 Total User: <?= $userCount ?></span>
            </nav>

            <!-- Content -->
            <section class="content">
                <h2>Data Usaha Terdaftar</h2>
                <div class="table-container">
                    <table id="usahaTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Usaha</th>
                                <th>Deskripsi</th>
                                <th>Alamat</th>
                                <th>Kategori</th>
                                <th>Ditambahkan oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data dari get_data.php akan dimasukkan di sini via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Footer -->
            <footer class="footer">
                <p>© 2025 PelokUsaha Admin Panel</p>
            </footer>
        </div>
    </div>
</body>

</html>