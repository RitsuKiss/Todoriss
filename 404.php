<?php
http_response_code(404);

session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="error">
        <h1>404</h1>
        <p>Oops! Halaman yang kamu cari tidak ditemukan.</p>
        <a href="/">Kembali ke Beranda</a>
    </div>
</body>

</html>