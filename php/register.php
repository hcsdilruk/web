<?php
include 'db.php';

$fullname = $_POST['fullname'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$genre = $_POST['genre'];

$sql = "INSERT INTO users (fullname, username, email, password, genre)
        VALUES ('$fullname', '$username', '$email', '$password', '$genre')";

if ($conn->query($sql) === TRUE) {
  echo "Account created successfully!";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
