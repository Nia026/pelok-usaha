<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = htmlspecialchars($_POST["nama"]);
  $email = htmlspecialchars($_POST["email"]);
  $subjek = htmlspecialchars($_POST["subjek"]);
  $pesan = htmlspecialchars($_POST["pesan"]);

  $mail = new PHPMailer(true);

  try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Atur sesuai provider Anda
    $mail->SMTPAuth = true;
    $mail->Username = 'niarfebriar@gmail.com'; // Ganti dengan email pengirim
    $mail->Password = 'hhumpzwubtvkrfdz'; // Gunakan App Password, bukan password biasa
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom($email, $nama); // Email dari user
    $mail->addAddress('pelokusaha@gmail.com', 'Pelok Usaha'); // Email tim pengembang

    // Content
    $mail->isHTML(false);
    $mail->Subject = "[Pelok Usaha] $subjek";
    $mail->Body    = "Nama: $nama\nEmail: $email\n\nPesan:\n$pesan";

    $mail->send();
    $success = "Pesan berhasil dikirim!";
  } catch (Exception $e) {
    $error = "Pesan gagal dikirim. Silakan coba lagi.";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Form Bantuan - Pelok Usaha</title>
  <link rel="stylesheet" href="./assets/css/bantuan.css">
</head>

<body>
  <main class="container">
    <h2>Hubungi Kami</h2>
    <p>Form ini dapat Anda gunakan untuk melaporkan kendala, memberikan saran, atau pertanyaan umum.</p>

    <?php if ($success): ?>
      <div class="alert success"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="bantuan.php" class="bantuan-form">
      <label for="nama">Nama</label>
      <input type="text" name="nama" id="nama" required>

      <label for="email">Email Anda</label>
      <input type="email" name="email" id="email" required>

      <label for="subjek">Subjek</label>
      <input type="text" name="subjek" id="subjek" required>

      <label for="pesan">Pesan</label>
      <textarea name="pesan" id="pesan" rows="6" required></textarea>

      <button type="submit" class="btn-kirim">Kirim</button>
    </form>
  </main>
  <footer>
    <p>© <?= date("Y") ?> Pelok Usaha. All rights reserved.</p>
  </footer>
</body>

</html>