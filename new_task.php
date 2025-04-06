<?php
session_start();
require_once "config/koneksi.php"; // Sesuaikan dengan lokasi koneksi database

$userId = $_SESSION['user_id'];
$errorMessage = "";
$successMessage = "";

// Fungsi untuk menghasilkan ID unik dengan panjang 15 karakter
function generateUniqueId($conn)
{
    $unique = false;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 15;

    while (!$unique) {
        // Generate random string
        $uniqueId = '';
        for ($i = 0; $i < $length; $i++) {
            $uniqueId .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        // Periksa apakah ID sudah ada di database
        $checkQuery = "SELECT task_id FROM task WHERE task_id = '$uniqueId'";
        $result = $conn->query($checkQuery);

        if ($result->num_rows == 0) {
            $unique = true;
        }
    }

    return $uniqueId;
}

// Proses form jika metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    // Generate ID unik untuk task
    $taskId = generateUniqueId($conn);

    // Query untuk menyisipkan data task
    $sql = "INSERT INTO task (task_id, user_id, judul, deskripsi, priority, deadline) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissss", $taskId, $userId, $judul, $deskripsi, $priority, $deadline);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Task berhasil ditambahkan!";
        header("Location: index.php"); // Redirect ke index.php
        exit();
    }
}
    
?>