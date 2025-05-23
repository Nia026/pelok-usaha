<?php
include 'config/database.php';
session_start();

$id = $_POST['id'];
mysqli_query($koneksi, "DELETE FROM businesses WHERE id_business = $id");

header("Location: user_dashboard.php");
