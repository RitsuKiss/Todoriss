<?php
include 'config/koneksi.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $query = "SELECT status FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    echo $task['status'];
}
?>
