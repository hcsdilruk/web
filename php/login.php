<?php
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  if (password_verify($password, $user['password'])) {
    echo "Login successful! Welcome, " . $user['fullname'];
    // session_start(); // optional for logged-in sessions
  } else {
    echo "Incorrect password.";
  }
} else {
  echo "User not found.";
}

$conn->close();
?>
