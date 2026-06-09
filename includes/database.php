<?php
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "drop_the_beat_db";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>