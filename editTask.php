<?php
header("X-Frame-Options: ALLOWALL");
?>
<?php
include 'config/koneksi.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Ambil data task berdasarkan ID
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

// Jika form dikirim, update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    $query = "UPDATE task SET judul = ?, deskripsi = ?, deadline = ? WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $judul, $deskripsi, $deadline, $task_id);

    if ($stmt->execute()) {
        header("Location: index.php");
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
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>

    <h2>Edit Task</h2>
    <form method="POST">
        <label>Judul Task:</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($task['judul']) ?>" required>
        
        <label>Deskripsi Task:</label>
        <textarea name="deskripsi" required><?= htmlspecialchars($task['deskripsi']) ?></textarea>
        
        <label>Deadline:</label>
        <input type="date" name="deadline" value="<?= htmlspecialchars($task['deadline']) ?>" required>

        <button type="submit">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
