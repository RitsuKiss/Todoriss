<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    $query = "UPDATE task SET judul = ?, deskripsi = ?, deadline = ? WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $judul, $deskripsi, $deadline, $task_id);

    if ($stmt->execute()) {
        header("Location: yourtask&" . $task['task_id']);
        exit();
    } else {
        echo "Gagal memperbarui task: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Edit Task</h2><span class="close" onclick="closePopup('popup3')">&times;</span>
        <form method="POST" class="edit-task-form">
            <label for="judul">Judul Task:</label>
            <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($task['judul']) ?>" required>

            <label for="deskripsi">Deskripsi Task:</label>
            <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($task['deskripsi']) ?></textarea>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" required>

            <div class="button-group">
                <button type="submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>

</html>