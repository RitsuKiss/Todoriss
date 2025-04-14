<?php
include 'config/koneksi.php';

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

$query = "SELECT username, photo FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];

    if (!empty($_FILES['photo']['name'])) {
        $uploadDir = "uploads/f{$userId}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $photoName = basename($_FILES['photo']['name']);
        $uploadFile = $uploadDir . $photoName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $update = "UPDATE users SET username = ?, photo = ? WHERE id = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ssi", $newUsername, $photoName, $userId);
        } else {
            echo "<script>alert('Upload foto gagal.');</script>";
        }
    } else {
        $update = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("si", $newUsername, $userId);
    }

    if ($stmt->execute()) {
    $_SESSION['username'] = $newUsername;
    if (!empty($photoName)) {
        $_SESSION['photo'] = $photoName;
    }
    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('Gagal memperbarui profil');</script>";
}

}
?>

<div class="form-container">
<span class="close" onclick="closePopup('popup4')">&times;</span>
    <form action="" method="POST" enctype="multipart/form-data">
    <h2>Edit Profil</h2>

    <label>Username:</label>
    <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>" required>

    <label>Foto Profil:</label>
    
    <input type="file" name="photo" id="photo" accept="image/*" style="display: none;" onchange="previewImage(event)">
    
    <label for="photo">
        <img 
            id="photoPreview"
            src="uploads/f<?php echo $userId; ?>/<?php echo $userData['photo']; ?>" 
            width="100" 
            height="100"
            alt="Klik untuk ganti foto"
            style="cursor: pointer; border-radius: 20px;"
            title="Klik untuk ganti foto"
        >
    </label>

    <button type="submit">Simpan</button>
</form>
</div>
