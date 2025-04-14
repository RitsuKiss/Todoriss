<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

$suming = "SELECT 
            COUNT(*) AS total_task,
            SUM(status = 'Finished') AS cleared,
            SUM(status = 'Late Complete') AS late_complete,
            SUM(status = 'Not Cleared') AS not_cleared
          FROM task WHERE user_id = ?";
$stmtp = $conn->prepare($suming);
$stmtp->bind_param("i", $userId);
$stmtp->execute();
$results = $stmtp->get_result();
$datas = $results->fetch_assoc();

$total_task = $datas['total_task'] ?? 0;
$cleared = $datas['cleared'] ?? 0;
$late_complete = $datas['late_complete'] ?? 0;
$not_cleared = $datas['not_cleared'] ?? 0;

$stmtp->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do Riss</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="sidebar">
        <?php include 'sidebar.php'; ?>
    </div>
    <div class="main">
        <div class="contop">
            <div class="topleft">
                <img id="userpic" src="uploads/f<?php echo $userId; ?>/<?php echo $_SESSION['photo']; ?>">
            </div>

            <div class="topright">
                <div class="usname"><?php echo $_SESSION['username']; ?></div>
                <div class="id">UID: <?php echo $userId; ?></div>
            </div>
        </div>
    </div>
    <div class="conbott">
        <div class="information">
            <div class="total">
                <div class="title">Task</div>
                <div class="num"><?php echo $total_task; ?></div>
            </div>
            <div class="total">
                <div class="title">Cleared</div>
                <div class="num"><?php echo $cleared; ?></div>
            </div>
            <div class="total">
                <div class="title">Late</div>
                <div class="num"><?php echo $late_complete; ?></div>
            </div>
            <div class="total">
                <div class="title">Not Cleared</div>
                <div class="num"><?php echo $not_cleared; ?></div>
            </div>
        </div>
        <div class="conbutt">
            <button class="editProf" onclick="edprofile()">Edit Profile</button>
            <button class="logout"  onclick="logout()">Log Out</button>
            <button class="setting" onclick="setting()"><img src="asset/Search.png" alt=""></button>
        </div>
    </div>
</body>

</html>
