<?php
include 'config/koneksi.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $query = "SELECT * FROM task WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $task_id);
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
$stmt->bind_param("s", $task_id);
$stmt->execute();
$subtaskResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To DO Riss</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <div class="newTask">
            <h1><?php echo htmlspecialchars($task['judul']); ?></h1>
            <button onclick="openPopup('popup2')">
                <h2> + </h2>
            </button>
        <div class="taskbutt">
            <a class="edsub" onclick="openPopup('popup3')">
                Edit</a>

            <div class="delsub">
                <a href="delete_task.php?task_id=<?= $task['task_id'] ?>" onclick="return confirm('Yakin ingin menghapus task ini?')">Hapus</a>
            </div>
        </div>
        <div id="popup3" class="modal">
        <?php include 'editTask.php'; ?>
    </div>
    </div>
    </div>


    <div class="yourTask">
        <div class="kosong"></div>

        <?php if ($subtaskResult->num_rows > 0): ?>
            <?php while ($subtask = $subtaskResult->fetch_assoc()): ?>
                <div class="task">
                    <input type="checkbox"
                        data-subtask-id="<?php echo htmlspecialchars($subtask['subtask_id']); ?>"
                        onchange="updateSubtask(this)"
                        <?php echo ($subtask['status'] === 'check') ? 'checked' : ''; ?>>
                    <strong>
                        <h1><?php echo htmlspecialchars($subtask['judul']); ?></h1>
                    </strong>
                    <p><?php echo nl2br(htmlspecialchars($subtask['deskripsi'])); ?></p>
                    <span id="status-<?php echo htmlspecialchars($subtask['subtask_id']); ?>"
                        style="color: <?php echo ($subtask['status'] === 'check') ? 'green' : 'red'; ?>">
                        <?php echo ($subtask['status'] === 'check') ? "✔ Selesai" : "❌ Belum Selesai"; ?>
                    </span>
                    <div class="taskbutt">
                        <div class="delsub">
                            <a href="delete_subtask.php?subtask_id=<?= htmlspecialchars($subtask['subtask_id']); ?>" onclick="return confirm('Yakin ingin menghapus subtask ini?')">Hapus</a>
                        </div>
                        
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="task">
                <p style="color: gray; font-size: 14px;">Belum ada subtask</p>
            </div>
        <?php endif; ?>
    </div>
    <div id="popup2" class="modal">
        <?php include 'newSform.php'; ?>
    </div>
</body>

<script>
    function updateSubtask(checkbox) {
        let subtaskId = checkbox.getAttribute("data-subtask-id");
        let status = checkbox.checked ? 'check' : 'not check';

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "chekSubtask.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let statusLabel = document.getElementById("status-" + subtaskId);
                statusLabel.textContent = (status === "check") ? "✔ Selesai" : "❌ Belum Selesai";
                statusLabel.style.color = (status === "check") ? "green" : "red";
            }
        };

        xhr.send("subtask_id=" + encodeURIComponent(subtaskId) + "&status=" + status);
    }
</script>

</html>