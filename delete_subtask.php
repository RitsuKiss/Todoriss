<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$task_id = isset($_GET['task_id']) ? $_GET['task_id'] : null;
$subtask_id = isset($_GET['subtask_id']) ? $_GET['subtask_id'] : null;

if ($subtask_id) {
    // Jika task_id tidak tersedia di URL, cari berdasarkan subtask_id
    if (!$task_id) {
        $query = "SELECT task_id FROM subtask WHERE subtask_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $subtask_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $task_id = $row['task_id'] ?? null;
        $stmt->close();
    }

    // Hapus subtask
    $deleteSubtask = "DELETE FROM subtask WHERE subtask_id = ?";
    $stmt = $conn->prepare($deleteSubtask);
    $stmt->bind_param("s", $subtask_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        $redirect_url = "yourtask&" . ($task_id ? "$task_id" : "");
        header("Location: $redirect_url");
        exit();
    } else {
        echo "Gagal menghapus subtask: " . $stmt->error;
    }
} else {
    echo "Subtask ID tidak ditemukan!";
}
?>
