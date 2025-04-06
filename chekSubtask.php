<?php
include 'config/koneksi.php';

if (isset($_POST['subtask_id']) && isset($_POST['status'])) {
    $subtask_id = $_POST['subtask_id'];
    $status = $_POST['status'];

    // Validasi status
    if (!in_array($status, ['check', 'not check'])) {
        die("Status tidak valid.");
    }

    // Update status subtask
    $query = "UPDATE subtask SET status = ? WHERE subtask_id = ?";
    $stmt_update_subtask = $conn->prepare($query);
    $stmt_update_subtask->bind_param("ss", $status, $subtask_id);
    if (!$stmt_update_subtask->execute()) {
        die("Gagal memperbarui subtask.");
    }
    $stmt_update_subtask->close();

    // Ambil task_id berdasarkan subtask_id
    $task_id_query = "SELECT task_id FROM subtask WHERE subtask_id = ?";
    $stmt_get_task_id = $conn->prepare($task_id_query);
    $stmt_get_task_id->bind_param("s", $subtask_id);
    $stmt_get_task_id->execute();
    $result = $stmt_get_task_id->get_result();
    if ($result->num_rows == 0) {
        die("Task tidak ditemukan.");
    }
    $task_id = $result->fetch_assoc()['task_id'];
    $stmt_get_task_id->close();

    // Hitung total subtask
    $total_subtask_query = "SELECT COUNT(*) AS total FROM subtask WHERE task_id = ?";
    $stmt_total_subtask = $conn->prepare($total_subtask_query);
    $stmt_total_subtask->bind_param("s", $task_id);
    $stmt_total_subtask->execute();
    $result = $stmt_total_subtask->get_result();
    $total_subtask = $result->fetch_assoc()['total'];
    $stmt_total_subtask->close();

    // Hitung jumlah subtask yang selesai
    $completed_subtask_query = "SELECT COUNT(*) AS completed FROM subtask WHERE task_id = ? AND status = 'check'";
    $stmt_completed_subtask = $conn->prepare($completed_subtask_query);
    $stmt_completed_subtask->bind_param("s", $task_id);
    $stmt_completed_subtask->execute();
    $result = $stmt_completed_subtask->get_result();
    $completed_subtask = $result->fetch_assoc()['completed'];
    $stmt_completed_subtask->close();

    // Cek deadline task
    $deadline_query = "SELECT deadline FROM task WHERE task_id = ?";
    $stmt_get_deadline = $conn->prepare($deadline_query);
    $stmt_get_deadline->bind_param("s", $task_id);
    $stmt_get_deadline->execute();
    $result = $stmt_get_deadline->get_result();
    $deadline = $result->fetch_assoc()['deadline'];
    $stmt_get_deadline->close();

    $current_date = date('Y-m-d'); // Tanggal hari ini

    // Tentukan status task berdasarkan kondisi
    if ($completed_subtask == $total_subtask) {
        $new_status = ($current_date <= $deadline) ? "Finished" : "Late Complete";
    } else {
        $new_status = ($current_date > $deadline) ? "Not Cleared" : "On Progress";
    }

    // Update status task
    $update_task_query = "UPDATE task SET status = ? WHERE task_id = ?";
    $stmt_update_task = $conn->prepare($update_task_query);
    $stmt_update_task->bind_param("ss", $new_status, $task_id);
    if (!$stmt_update_task->execute()) {
        die("Gagal memperbarui status task.");
    }
    $stmt_update_task->close();

    echo "Success";
} else {
    die("Parameter tidak lengkap.");
}
?>
