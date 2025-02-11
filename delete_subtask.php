<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['subtask_id'])) {
    $subtask_id = $_GET['subtask_id'];

    // Hapus subtask berdasarkan ID
    $deleteSubtask = "DELETE FROM subtask WHERE subtask_id = ?";
    $stmt = $conn->prepare($deleteSubtask);
    $stmt->bind_param("i", $subtask_id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect setelah berhasil
        exit();
    } else {
        echo "Gagal menghapus subtask: " . $conn->error;
    }
} else {
    echo "Subtask ID tidak ditemukan!";
}
?>
