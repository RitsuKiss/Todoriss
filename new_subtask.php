<?php
include 'config/koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Periksa apakah task_id ada di URL
if (!isset($_GET['task_id'])) {
    die("Error: ID Task tidak ditemukan.");
}

$task_id = $_GET['task_id'];
$errorMessage = "";
$successMessage = "";

// Fungsi untuk menghasilkan ID unik 15 karakter
function generateUniqueId($conn) {
    $unique = false;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 15;

    while (!$unique) {
        $uniqueId = '';
        for ($i = 0; $i < $length; $i++) {
            $uniqueId .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        // Periksa apakah ID sudah ada di database
        $checkQuery = "SELECT subtask_id FROM subtask WHERE subtask_id = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $uniqueId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $unique = true;
        }
        $stmt->close();
    }

    return $uniqueId;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    if (empty($judul) || empty($deskripsi)) {
        $errorMessage = "Judul dan deskripsi harus diisi.";
    } else {
        // Generate unique ID untuk subtask
        $subtaskId = generateUniqueId($conn);

        // Insert subtask ke database
        $sql = "INSERT INTO subtask (subtask_id, task_id, judul, deskripsi) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $subtaskId, $task_id, $judul, $deskripsi);

        if ($stmt->execute()) {
            // Redirect ke halaman yourtask dengan task_id
            header("Location: yourtask&" . $task_id);
            exit();
        } else {
            $errorMessage = "Terjadi kesalahan: " . $conn->error;
        }
        $stmt->close();
    }
}
?>
