<?php
include 'config/koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Periksa apakah id_task ada di URL
if (!isset($_GET['task_id'])) {
    die("ID Task tidak ditemukan.");
}

$task_id = $_GET['task_id'];
$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // Insert subtask ke database
    $sql = "INSERT INTO subtask (task_id, judul, deskripsi) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $task_id, $judul, $deskripsi);

    if ($stmt->execute()) {
        $successMessage = "Subtask berhasil ditambahkan!";
    } else {
        $errorMessage = "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Subtask</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Subtask</h2>
        <?php if ($errorMessage): ?>
            <p style="color: red;"> <?php echo $errorMessage; ?> </p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p style="color: green;"> <?php echo $successMessage; ?> </p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="judul" placeholder="Judul Subtask" required>
            <textarea name="deskripsi" placeholder="Deskripsi Subtask" required></textarea>
            <button type="submit">Tambah Subtask</button>
        </form>
    </div>
</body>
</html>
