<?php
$currentPage = htmlspecialchars(basename($_SERVER['PHP_SELF']));

$current_date = date("Y-m-d");

$sql_update = "UPDATE task
               SET status = CASE
                   WHEN deadline < '$current_date' AND status != 'Finished' AND status != 'Late Complete' THEN 'Not Cleared'
                   ELSE status
               END
               WHERE user_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("i", $userId);
$stmt_update->execute();
?>
<div class="sidebar">
    <div class="profile">
        <img src="uploads/f<?php echo $userId; ?>/<?php echo $_SESSION['photo']; ?>" alt="Your Photo">
        <label>
            <h1><?php echo $_SESSION['username']; ?></h1>
            <p>
            <h6>UID: <?php echo $_SESSION['user_id']; ?></p>
            </h6>
        </label>
    </div>

    <div class="navigation">

        <a href="index.php">
            <div class="nav-icon <?php echo ($currentPage == 'index.php' || $currentPage == 'yourTask.php') ? 'active' : ''; ?>">
                <img src="asset/Home.png" alt="ini rumah">
                <label>Your Task</label>
            </div>
        </a><a href="priority.php">
            <div class="nav-icon <?php echo ($currentPage == 'priority.php') ? 'active' : ''; ?>">
                <img src="asset/priority.png" alt="Priority">
                <label>Priority</label>
            </div>
        </a><a href="deadlined.php">
            <div class="nav-icon <?php echo ($currentPage == 'deadlined.php') ? 'active' : ''; ?>">
                <img src="asset/calendar.png" alt="Deadlined">
                <label>Deadlined</label>
            </div>
        </a><a href="onProgress.php">
            <div class="nav-icon <?php echo ($currentPage == 'onProgress.php') ? 'active' : ''; ?>">
                <img src="asset/on progres.png" alt="On Progress">
                <label>On Progress</label>
            </div>
        </a><a href="finished.php">
            <div class="nav-icon <?php echo ($currentPage == 'finished.php') ? 'active' : ''; ?>">
                <img src="asset/finished.png" alt="Finished">
                <label>Finished</label>
            </div>
        </a><a href="lateComplete.php">
            <div class="nav-icon <?php echo ($currentPage == 'lateComplete.php') ? 'active' : ''; ?>">
                <img src="asset/Late complete.png" alt="Late Complete">
                <label>Late Complete</label>
            </div>
        </a><a href="notCleared.php">
            <div class="nav-icon <?php echo ($currentPage == 'notCleared.php') ? 'active' : ''; ?>">
                <img src="asset/not cleared.png" alt="Not cleared">
                <label>Not cleared</label>
            </div>
        </a>
    </div>
    <div class="bottom">
        <div class="search">
            <form action="search.php" method="GET">
                <img src="asset/Search.png" alt="">
                <input type="text" name="Search" placeholder="Search Task" required>
                <button type="submit">></button>
            </form>
        </div>
        <div class="menu">
            <h2 onclick="logout()">:</h2>
        </div>
    </div>
</div>
<script src="js/script.js"></script>