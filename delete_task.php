<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $deleteSubtasks = "DELETE FROM subtask WHERE task_id = ?";
    $stmt1 = $conn->prepare($deleteSubtasks);
    $stmt1->bind_param("s", $task_id);
    $stmt1->execute();


    $deleteTask = "DELETE FROM task WHERE task_id = ?";
    $stmt2 = $conn->prepare($deleteTask);
    $stmt2->bind_param("s", $task_id);

    if ($stmt2->execute()) {
        header("Location: /");
        exit();
    } else {
        echo "Gagal menghapus task: " . $conn->error;
    }
} else {
    echo "Task ID tidak ditemukan!";
}
?>
