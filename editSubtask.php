<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_GET['subtask_id'])) {
    $subtask_id = $_GET['subtask_id'];

    // Ambil data subtask berdasarkan ID
    $query = "SELECT * FROM subtask WHERE subtask_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $subtask_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subtask = $result->fetch_assoc();

    if (!$subtask) {
        die("Subtask tidak ditemukan.");
    }
} else {
    die("ID Subtask tidak diberikan.");
}

// Jika form dikirim, update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE subtask SET judul = ?, deskripsi = ? WHERE subtask_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $judul, $deskripsi, $subtask_id);

    if ($stmt->execute()) {
        header("Location: yourtask&" . $subtask['task_id']);
        exit();
    } else {
        echo "Gagal memperbarui subtask: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Subtask</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="form-container">
        <h2>Edit Subtask</h2>
        <form method="POST" class="edit-subtask-form">
            <label for="judul">Judul Subtask:</label>
            <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($subtask['judul']) ?>" required>

            <label for="deskripsi">Deskripsi Subtask:</label>
            <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($subtask['deskripsi']) ?></textarea>

            <div class="button-group">
                <button type="submit">Simpan Perubahan</button>
                <a href="index.php" class="cancel-button">Batal</a>
            </div>
        </form>
    </div>

</body>

</html>