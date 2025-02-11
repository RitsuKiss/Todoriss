<?php
include 'config/koneksi.php';

if (isset($_GET['subtask_id'])) {
    $subtask_id = $_GET['subtask_id'];

    // Ambil data subtask berdasarkan ID
    $query = "SELECT * FROM subtask WHERE subtask_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $subtask_id);
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
    $stmt->bind_param("ssi", $judul, $deskripsi, $subtask_id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redirect ke halaman task
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
    <h2>Edit Subtask</h2>
    <form method="POST">
        <label>Judul Subtask:</label>
        <input type="text" name="judul" value="<?= htmlspecialchars($subtask['judul']) ?>" required>
        
        <label>Deskripsi Subtask:</label>
        <textarea name="deskripsi" required><?= htmlspecialchars($subtask['deskripsi']) ?></textarea>
        
        <button type="submit">Simpan Perubahan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>