<?php
include 'config/database.php';

// Ambil semua bisnis
$search = $_GET['q'] ?? '';
$searchQuery = $search ? "AND (b.name LIKE '%$search%' OR b.address LIKE '%$search%')" : '';

$query = "SELECT b.*, u.username FROM businesses b 
          JOIN users u ON b.id_user = u.id_user 
          WHERE 1=1 $searchQuery";
$result = mysqli_query($koneksi, $query);

$businesses = [];
while ($row = mysqli_fetch_assoc($result)) {
  $businesses[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pelok Usaha</title>
  <link rel="stylesheet" href="./assets/css/landing_page.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
</head>

<body>
  <nav class="navbar">
    <div class="container">
      <img src="images/logo2.png" alt="Pelok Usaha Logo" class="logo">
      <div class="auth-buttons">
        <a href="login.php" class="btn btn-signin">Sign in</a>
        <a href="register.php" class="btn btn-register">Register</a>
      </div>
    </div>
  </nav>

  <header class="hero">
    <div class="container">
      <h1>Selamat Datang di <span>PELOK USAHA</span></h1>
      <p class="subtitle">Peta Bisnis Lokal, Jalan Menuju Kesuksesan.</p>
    </div>
  </header>

  <section class="vision">
    <div class="container">
      <h2>OUR VISION</h2>
      <ol>
        <li>Menyediakan platform WebGIS interaktif untuk memetakan lokasi UMKM di Indonesia.</li>
        <li>Mempermudah pelaku UMKM untuk mempromosikan usahanya secara mandiri.</li>
        <li>Menyediakan sistem login dan manajemen usaha berbasis web yang aman.</li>
        <li>Membantu masyarakat menemukan usaha lokal dengan lebih cepat dan efisien.</li>
      </ol>
    </div>
  </section>

  <section class="search-section">
    <div class="container">
      <h2>Lihat UMKM Disekitarmu!</h2>
      <form id="search-form">
        <input type="text" id="search-input" placeholder="Ketik lokasimu atau nama usaha">
        <button type="submit">Cari UMKM</button>
      </form>
    </div>
  </section>

  <section class="map-display">
    <div class="container">
      <div class="sidebar">
        <h3>UMKM Terdaftar</h3>
        <div id="umkm-list">
          <!-- UMKM list dari JS -->
        </div>
      </div>
      <div id="map"></div>
    </div>
  </section>

  <footer class="footer">
    <p>© 2025 Pelok Usaha - Semua Hak Dilindungi</p>
  </footer>

  <script>
    const map = L.map('map').setView([-7.250445, 112.768845], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap'
    }).addTo(map);

    fetch('get_data.php')
      .then(response => response.json())
      .then(data => {
        const listContainer = document.getElementById('umkm-list');
        listContainer.innerHTML = '';
        data.forEach(b => {
          const marker = L.marker([b.latitude, b.longitude]).addTo(map)
            .bindPopup(`<b>${b.name}</b><br>${b.description}<br>${b.category}`);

          const item = document.createElement('div');
          item.classList.add('umkm-item');
          item.innerHTML = `
                        <h4>${b.name}</h4>
                        <p>${b.address}</p>
                        <p><strong>Kategori:</strong> ${b.category}</p>
                        <p><strong>Deskripsi:</strong> ${b.description}</p>
                        <p><strong>Ditambahkan oleh:</strong> ${b.username}</p>
                    `;
          listContainer.appendChild(item);
        });
      });

    document.getElementById('search-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const keyword = document.getElementById('search-input').value.toLowerCase();
      fetch('get_data.php')
        .then(response => response.json())
        .then(data => {
          const filtered = data.filter(b =>
            b.name.toLowerCase().includes(keyword) ||
            b.address.toLowerCase().includes(keyword)
          );

          const listContainer = document.getElementById('umkm-list');
          listContainer.innerHTML = '';
          map.eachLayer(layer => {
            if (layer instanceof L.Marker) map.removeLayer(layer);
          });

          filtered.forEach(b => {
            L.marker([b.latitude, b.longitude]).addTo(map)
              .bindPopup(`<b>${b.name}</b><br>${b.description}<br>${b.category}`);

            const item = document.createElement('div');
            item.classList.add('umkm-item');
            item.innerHTML = `
                            <h4>${b.name}</h4>
                            <p>${b.address}</p>
                            <p><strong>Kategori:</strong> ${b.category}</p>
                            <p><strong>Deskripsi:</strong> ${b.description}</p>
                            <p><strong>Ditambahkan oleh:</strong> ${b.username}</p>
                        `;
            listContainer.appendChild(item);
          });
        });
    });
  </script>
</body>

</html>