<?php
// Koneksi ke database
include 'config/koneksi.php';
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil ID pengguna dari sesi
$userId = $_SESSION['user_id'];

// Ambil data task berdasarkan user_id
$sqlTask = "SELECT * FROM task WHERE user_id = ?";
$stmtTask = $conn->prepare($sqlTask);
$stmtTask->bind_param("i", $userId);
$stmtTask->execute();
$resultTask = $stmtTask->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Your Tasks</h2>
        <?php if ($resultTask->num_rows > 0): ?>
            <ul>
                <?php while ($task = $resultTask->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo htmlspecialchars($task['judul']); ?></h3>
                        <p><?php echo htmlspecialchars($task['deskripsi']); ?></p>
                        <p>Status: <?php echo htmlspecialchars($task['status']); ?></p>

                        <!-- Ambil data subtask berdasarkan task_id -->
                        <?php
                        $taskId = $task['task_id'];
                        $sqlSubtask = "SELECT * FROM subtask WHERE task_id = ?";
                        $stmtSubtask = $conn->prepare($sqlSubtask);
                        $stmtSubtask->bind_param("i", $taskId);
                        $stmtSubtask->execute();
                        $resultSubtask = $stmtSubtask->get_result();
                        ?>
                        
                        <?php if ($resultSubtask->num_rows > 0): ?>
                            <ul>
                                <?php while ($subtask = $resultSubtask->fetch_assoc()): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars($subtask['judul']); ?></strong>
                                        <p><?php echo htmlspecialchars($subtask['deskripsi']); ?></p>
                                        <p>Status: <?php echo htmlspecialchars($subtask['status']); ?></p>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p>No subtasks available.</p>
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No tasks available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
