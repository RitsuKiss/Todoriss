<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];


$sql = "SELECT * FROM task WHERE user_id = ? ORDER BY 
    CASE 
        WHEN priority = 'High' THEN 1 
        WHEN priority = 'Medium' THEN 2 
        ELSE 3 
    END";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$taskResult = $stmt->get_result();

$query = "SELECT * FROM subtask WHERE task_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();

while ($subtask = $result->fetch_assoc()) {
    $checked = ($subtask['status'] === 'check') ? 'checked' : '';
    echo '<label>
            <input type="checkbox" data-task-id="' . $task_id . '" onclick="updateSubtask(' . $subtask['subtask_id'] . ', this)" ' . $checked . '>
            ' . $subtask['judul'] . '
          </label><br>';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO Do Riss</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

header {
    background-color: #333;
    color: white;
    padding: 15px;
    text-align: center;
}

.container {
    width: 80%;
    margin: auto;
    background: white;
    padding: 20px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    margin-top: 20px;
}

.task {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 5px;
    background: #fff;
}

.task h3 {
    margin: 0;
    color: #333;
}

.status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 14px;
}

.status.Finished {
    background: blue;
    color: white;
}

.status.In-Progress {
    background: orange;
    color: white;
}

button {
    background: #007BFF;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 10px;
}

button:hover {
    background: #0056b3;
}

.logout {
    background: red;
    position: fixed;
    bottom: 20px;
    right: 20px;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background: white;
    margin: 10% auto;
    padding: 20px;
    width: 50%;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}

.close {
    float: right;
    font-size: 20px;
    cursor: pointer;
}

.subtask {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 5px;
    margin-top: 5px;
}

.subtask input {
    margin-right: 10px;
}

.subtask span {
    font-weight: bold;
}

    </style>
</head>

<body>
    <header>
        <div class="profile">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <p>UID: <?php echo $_SESSION['user_id']; ?></p>
        </div>
        <nav>
        </nav>
    </header>

    <div class="container">
        <h2>Daftar Task</h2>
        <button onclick="openPopup('newTask')">Tambah Task</button>
        <button onclick="openPopup('newSubtask')">Tambah SubTask</button>
        <br><br>

        <?php while ($task = $taskResult->fetch_assoc()): ?>
            <div class="task">
                <h3><?php echo htmlspecialchars($task['judul']); ?></h3>
                <a href="delete_task.php?task_id=<?= $task['task_id'] ?>" onclick="return confirm('Yakin ingin menghapus task ini?')">Hapus</a>
                <?php
                        $priority = $task['priority'];
                        if ($priority == 'High') {
                            echo "🔴 Tinggi";
                        } elseif ($priority == 'Medium') {
                            echo "🟡 Menengah";
                        } else {
                            echo "🟢 Rendah";
                        }
                    ?>
                <p>Deadline: <strong><?php echo htmlspecialchars($task['deadline']) ?></strong></p>
                <p><?php echo nl2br(htmlspecialchars($task['deskripsi'])); ?></p>
                <span class="status <?php echo str_replace(' ', '-', $task['status']); ?>">
                    <?php echo $task['status']; ?>
                </span>


                <?php
                $taskId = $task['task_id'];
                $subtaskSql = "SELECT * FROM subtask WHERE task_id = ?";
                $subtaskStmt = $conn->prepare($subtaskSql);
                $subtaskStmt->bind_param("i", $taskId);
                $subtaskStmt->execute();
                $subtaskResult = $subtaskStmt->get_result();
                ?>


                <?php if ($subtaskResult->num_rows > 0): ?>
                    <h4>Subtasks:</h4>
                    <?php while ($subtask = $subtaskResult->fetch_assoc()): ?>
                        <div class="subtask">
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
                    <p style="color: gray; font-size: 14px;">Belum ada subtask</p>
                <?php endif; ?>
                <button onclick="openPopup('editTask')">edit</button>
                <a href="<?php echo 'editTask.php?task_id=' . $taskId ?>">Edit Task</a>
            </div>
        <?php endwhile; ?>

    </div>

    <div id="newTask" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('newTask')">&times;</span>
            <iframe src="new_task.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>
    <div id="newSubtask" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('newSubtask')">&times;</span>
            <iframe src="new_subtask.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>

    <button class="logout" onclick="logout()">Logout</button>
    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

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

        function updateTaskStatus(taskId) {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "getTaskStatus.php?task_id=" + taskId, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let taskStatusLabel = document.getElementById("task-status-" + taskId);
                    taskStatusLabel.textContent = xhr.responseText;
                    taskStatusLabel.style.color = (xhr.responseText === "Finished") ? "blue" : "orange";
                }
            };

            xhr.send();
        }

        function openPopup(id) {
            document.getElementById(id).style.display = "block";
        }

        function closePopup(id) {
            document.getElementById(id).style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }
    </script>
</body>

</html>