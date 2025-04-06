<?php
if (empty($_SESSION['user_id'])) {
    die("Error: Session user_id tidak diatur.");
}

// Debug untuk memeriksa apakah user_id ada di database
$userId = $_SESSION['user_id'];
$sqlCheckUser = "SELECT * FROM users WHERE id = ?";
$stmtCheck = $conn->prepare($sqlCheckUser);
$stmtCheck->bind_param("i", $userId);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();
if ($resultCheck->num_rows == 0) {
    die("Error: User ID tidak ditemukan di tabel users.");
}

?>

<h1>p</h1>