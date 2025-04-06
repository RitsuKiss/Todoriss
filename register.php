<?php
include 'config/koneksi.php';

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
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

        header('Location: login.php');
        exit();
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
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
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <?php if ($errorMessage): ?>
            <p><?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
