<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];


if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $query = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $task_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();

    if (!$task) {
        die("Task tidak ditemukan.");
    }
} else {
    die("ID Task tidak diberikan.");
}

$query = "SELECT * FROM subtask WHERE task_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();


        $taskId = $task['task_id'];
        $subtaskSql = "SELECT * FROM subtask WHERE task_id = ?";
        $subtaskStmt = $conn->prepare($subtaskSql);
        $subtaskStmt->bind_param("i", $taskId);
        $subtaskStmt->execute();
        $subtaskResult = $subtaskStmt->get_result();
        ?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>To DO Riss</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <a href="<?php echo 'new_subtask.php?task_id=' . $task['task_id']; ?>">
        <div class="newTask">
            <h1><?php echo htmlspecialchars($task['judul']); ?></h1>
            <h2> + </h2>
        </div>
    </a>
    <div class="yourTask">


        <div class="kosong"></div>

        <?php if ($subtaskResult->num_rows > 0): ?>
            <?php while ($subtask = $subtaskResult->fetch_assoc()): ?>
                <div class="task">
                    <input type="checkbox"
                        onchange="updateSubtask(<?php echo $subtask['subtask_id']; ?>, this)"
                        <?php echo ($subtask['status'] === 'check') ? 'checked' : ''; ?>>
                    <strong><?php echo htmlspecialchars($subtask['judul']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($subtask['deskripsi'])); ?></p>
                    <span id="status-<?php echo $subtask['subtask_id']; ?>"
                        style="color: <?php echo ($subtask['status'] === 'check') ? 'green' : 'red'; ?>">
                        <?php echo ($subtask['status'] === 'check') ? "✔ Selesai" : "❌ Belum Selesai"; ?>
                    </span>
                    <a href="<?php echo 'editSubtask.php?subtask_id=' . $subtask['subtask_id']; ?>">Edit Subtask</a><a href="delete_subtask.php?subtask_id=<?= $subtask['subtask_id'] ?>" onclick="return confirm('Yakin ingin menghapus subtask ini?')">Hapus</a>

                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="task">
            <p style="color: gray; font-size: 14px;">Belum ada subtask</p>
            </div>
        <?php endif; ?>
    </div>
</body>
<script>
    function updateSubtask(subtaskId, checkbox) {
            let status = checkbox.checked ? 'check' : 'not check';
            let taskId = checkbox.dataset.taskId;

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "chekSubtask.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let statusLabel = document.getElementById("status-" + subtaskId);
                    statusLabel.textContent = (status === "check") ? "✔ Selesai" : "❌ Belum Selesai";
                    statusLabel.style.color = (status === "check") ? "green" : "red";

                    // Perbarui status task setelah subtask diubah
                    updateTaskStatus(taskId);
                }
            };

            xhr.send("subtask_id=" + subtaskId + "&status=" + status);
        }
</script>

</html>