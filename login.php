<?php
session_start();
include 'config/koneksi.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = md5($password); // Tetap menggunakan MD5 untuk prototipe

    // Cek login berdasarkan username, email, atau ID
    $sql = "SELECT * FROM users WHERE (username='$input' OR email='$input' OR id='$input') AND pass='$passwordHash'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Simpan informasi ke dalam sesi
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['photo'] = $user['photo'];
        $_SESSION['role'] = $user['role'];

        // Redirect sesuai role
        if ($user['role'] == 'admin') {
            header('Location: ./adminpanel/'); // Admin dashboard
        } else {
            header('Location: index.php'); // User dashboard
        }
        exit();
    } else {
        $errorMessage = "Password incorrect or account does not exist";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    </style>
</head>
<body>
        <div class="container">
            <h2>Log In</h2>
            <form method="post" action="">
                <input type="text" name="email" placeholder="Email/Username/ID" required>
                <div class="form-group password-container">
                    <input type="password" name="password" id="password" placeholder="<?php echo $errorMessage ?: 'Password'; ?>" required>
                    <i class="fas fa-eye" id="togglePassword" onclick="togglePassword()"></i>
                </div>
                <div class="reg">
                <h5>ga punya akun?</h5>
                <a href="register.php">bikin dong</a>
                </div>
                <button type="submit">Log In</button>
            </form>
        </div>
        <script>
                        </script>

    <script src="js/login.js"></script>
</body>
</html>
