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

$sql = "SELECT * FROM task WHERE user_id = ? AND status = 'Late Complete';";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$taskResult = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do Riss</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="newTask">
            <h1>To Do Riss</h1>
            <button onclick="openPopup('popup1')">
            <h2> + </h2>
            </button>
        </div>
    <div class="yourTask">
        <div class="kosong"></div>
        <?php while ($task = $taskResult->fetch_assoc()): ?>
            <a href="<?php echo 'yourtask&' . $task['task_id']; ?>">
                <div class="task">
                    <div class="content">
                        <div class="judul">
                            <h1><?php echo htmlspecialchars($task['judul']); ?></h1>
                        </div>
                        <div class="deadline">
                            <p>Deadline: <strong><?php echo htmlspecialchars($task['deadline']) ?></strong></p>
                        </div>
                        <div class="deskripsi">
                            <p><?php echo nl2br(htmlspecialchars($task['deskripsi'])); ?></p>
                        </div>
                        <div class="status">
                            <span class="status <?php echo str_replace(' ', '-', $task['status']); ?>">
                                <?php echo $task['status']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>

    </div>
    <div id="popup1" class="modal">
        <?php include 'newTform.php'; ?>
    </div>


</body>
</html>