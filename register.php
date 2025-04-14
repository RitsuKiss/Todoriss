<?php
session_start();
include 'config/koneksi.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi username
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errorMessage = "Username hanya boleh berisi huruf dan angka (tanpa simbol atau spasi).";
    }
    // Validasi password
    elseif (strlen($password) < 8 || !preg_match('/^[a-zA-Z0-9]+$/', $password)) {
        $errorMessage = "Password harus minimal 8 karakter dan hanya boleh huruf dan angka (tanpa simbol).";
    }
    // Validasi email domain
    elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@rawr\.com$/', $email)) {
        $errorMessage = "Email harus menggunakan domain @rawr.com.";
    }
    // Cek username sudah ada
    else {
        $checkUsername = "SELECT * FROM users WHERE username = '$username'";
        $resultUsername = $conn->query($checkUsername);
        if ($resultUsername->num_rows > 0) {
            $errorMessage = "Username sudah digunakan. Silakan pilih yang lain.";
        }
        // Cek email sudah ada
        else {
            $checkEmail = "SELECT * FROM users WHERE email = '$email'";
            $resultEmail = $conn->query($checkEmail);
            if ($resultEmail->num_rows > 0) {
                $errorMessage = "Email sudah digunakan. Silakan gunakan email lain.";
            } else {
                $passwordHash = md5($password);

                function generateUserId($conn) {
                    $unique = false;
                    while (!$unique) {
                        $userId = mt_rand(10000000, 99999999);
                        $checkQuery = "SELECT id FROM users WHERE id = '$userId'";
                        $result = $conn->query($checkQuery);
                        if ($result->num_rows == 0) {
                            $unique = true;
                        }
                    }
                    return $userId;
                }

                $userId = generateUserId($conn);

                $sql = "INSERT INTO users (id, username, email, pass) VALUES ('$userId', '$username', '$email', '$passwordHash')";

                if ($conn->query($sql) === TRUE) {
                    $userFolder = "uploads/f" . $userId;
                    if (!file_exists($userFolder)) {
                        mkdir($userFolder, 0777, true);
                    }

                    $_SESSION['register_success'] = true;
                    header('Location: register.php');
                    exit();
                } else {
                    $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="logcontainer">
        <h2>Register</h2>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required
                pattern="[a-zA-Z0-9]+" title="Username hanya boleh huruf dan angka (tanpa simbol atau spasi)">
            <input type="text" name="email" placeholder="Email (@rawr.com)" required
                pattern="[a-zA-Z0-9._%+-]+@rawr\.com" title="Gunakan email dengan domain @rawr.com">
            <input type="password" name="password" placeholder="Password" required
                pattern="[a-zA-Z0-9]{8,}" title="Minimal 8 karakter, tanpa simbol (hanya huruf dan angka)">
            <button type="submit">Register</button>
        </form>

        <?php if ($errorMessage): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['register_success']) && $_SESSION['register_success']): ?>
            <script>
                alert("Pendaftaran berhasil! Kamu akan dialihkan ke halaman login...");
                setTimeout(function () {
                    window.location.href = "login.php";
                }, 1000);
            </script>
            <?php unset($_SESSION['register_success']); ?>
        <?php endif; ?>
    </div>
</body>
</html>
