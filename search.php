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
$tasks = [];

if (isset($_GET['Search'])) {
    $keyword = "%" . $_GET['Search'] . "%";
    
    $sql = "SELECT * FROM task WHERE user_id = ? AND (judul LIKE ? OR deskripsi LIKE ?) ORDER BY created_at DESC;";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userId, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Tugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    
    <div class="newTask">
            <h1>Hasil Pencarian</h1>
            <h2>
                <img src="asset/Search.png" style="filter: invert();">
            </h2>
            
        </div>

    <div class="yourTask">
        <div class="kosong"></div>
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
                <a href="<?php echo 'yourTask.php?task_id=' . $task['task_id']; ?>">
                    <div class="task">
                        <div class="content">
                            <div class="judul">
                                <h1><?php echo htmlspecialchars($task['judul']); ?></h1>
                            </div>
                            <div class="deadline">
                                <p>Deadline: <strong><?php echo htmlspecialchars($task['deadline']); ?></strong></p>
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
            <?php endforeach; ?>
        <?php else: ?>
            <div class="task">
            <p class="not-found">Data tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
