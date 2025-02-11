<?php
include 'config/koneksi.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$errorMessage = "";
$successMessage = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO task (user_id, judul, deskripsi, priority, deadline) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $userId, $judul, $deskripsi, $priority, $deadline);

    if ($stmt->execute()) {
        $successMessage = "Task berhasil ditambahkan!";
    } else {
        $errorMessage = "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Task</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Task</h2>
        <?php if ($errorMessage): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p style="color: green;"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="judul" placeholder="Judul Task" required>
            <textarea name="deskripsi" placeholder="Deskripsi Task" required></textarea>
            <select name="priority" required>
                <option value="High">🔴 High</option>
                <option value="Medium" selected>🟡 Medium</option>
                <option value="Low">🟢 Low</option>
            </select>
            <label>Deadline:</label>
            <input type="date" name="deadline" required min="<?php echo date('Y-m-d'); ?>">
            <button type="submit">Tambah Task</button>
        </form>
    </div>
</body>

<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        var deadline = document.querySelector('input[name="deadline"]').value;
        var currentDate = new Date().toISOString().split('T')[0];

        if (deadline < currentDate) {
            alert("Tanggal deadline tidak boleh lebih kecil dari hari ini.");
            event.preventDefault();
        }
    });
</script>

</html>
