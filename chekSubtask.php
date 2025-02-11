<?php
include 'config/koneksi.php';

if (isset($_POST['subtask_id']) && isset($_POST['status'])) {
    $subtask_id = $_POST['subtask_id'];
    $status = $_POST['status'];

    // Update status subtask
    $query = "UPDATE subtask SET status = ? WHERE subtask_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $subtask_id);
    $stmt->execute();

    // Cek apakah semua subtask sudah selesai
    $task_id_query = "SELECT task_id FROM subtask WHERE subtask_id = ?";
    $stmt = $conn->prepare($task_id_query);
    $stmt->bind_param("i", $subtask_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task_id = $result->fetch_assoc()['task_id'];

    // Hitung total subtask & subtask yang selesai
    $total_subtask_query = "SELECT COUNT(*) AS total FROM subtask WHERE task_id = ?";
    $stmt = $conn->prepare($total_subtask_query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_subtask = $result->fetch_assoc()['total'];

    $completed_subtask_query = "SELECT COUNT(*) AS completed FROM subtask WHERE task_id = ? AND status = 'check'";
    $stmt = $conn->prepare($completed_subtask_query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $completed_subtask = $result->fetch_assoc()['completed'];

    // Cek deadline
    $deadline_query = "SELECT deadline FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($deadline_query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $deadline = $result->fetch_assoc()['deadline'];

    $current_date = date('Y-m-d'); // Tanggal hari ini

    // Tentukan status task berdasarkan deadline dan subtask
    if ($completed_subtask == $total_subtask) {
        $new_status = ($current_date <= $deadline) ? "Finished" : "Late Complete";
    } else {
        $new_status = ($current_date > $deadline) ? "Not Cleared" : "On Progress";
    }

    // Update status task
    $update_task_query = "UPDATE task SET status = ? WHERE task_id = ?";
    $stmt = $conn->prepare($update_task_query);
    $stmt->bind_param("si", $new_status, $task_id);
    $stmt->execute();

    echo "Success";
}
?>
