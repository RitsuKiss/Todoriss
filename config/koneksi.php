<?php
$conn = mysqli_connect("localhost", "root", "", "todoriss");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
